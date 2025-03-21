<?php

declare(strict_types = 1);

class Cloud9Software_Sniffs_Whitespace_MultiLineStatementIndentSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{
    
    public function register()
    {
        return [
            T_OBJECT_OPERATOR,
            T_STRING,
            T_LNUMBER,
            T_VARIABLE,
            T_CONSTANT_ENCAPSED_STRING,
            T_ARRAY,
            T_ARRAY_HINT,
            T_OPEN_SHORT_ARRAY
        ];
    }
    
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $firstTokenOnLineIndex = $phpcsFile->findFirstOnLine([T_WHITESPACE], $stackPtr, true);
        $thisIsFirstTokenOnLine = ($firstTokenOnLineIndex == $stackPtr);
        
        $previousTokenIsString = $stackPtr && $tokens[$stackPtr - 1]['code'] == T_CONSTANT_ENCAPSED_STRING;
        $thisTokenIsString = $tokens[$stackPtr]['code'] == T_CONSTANT_ENCAPSED_STRING;
        $thisIsContinuationOfMultiLineString = $thisTokenIsString && $previousTokenIsString;
        
        if ($thisIsFirstTokenOnLine && !$thisIsContinuationOfMultiLineString) {
            $statementBeginningIndex = $this->getFirstTokenInStatementIndex($phpcsFile, $stackPtr);
            
            if ($statementBeginningIndex && $statementBeginningIndex != $firstTokenOnLineIndex) {
                $statementBeginningToken = $tokens[$statementBeginningIndex];
                
                $expectedIndent = $statementBeginningToken['column'] + 4;
                $firstTokenOnLine = $tokens[$firstTokenOnLineIndex];
                
                if ($expectedIndent != $firstTokenOnLine['column']) {
                    $phpcsFile->addError(
                        "Indent incorrect; expected " . ($expectedIndent - 1)
                        . ', found ' . ($firstTokenOnLine['column'] - 1),
                        $stackPtr,
                        'MultiLineStatementIndent');
                }
            }
        }
    }
    
    /**
     * 
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $stackPtr
     */
    private function getFirstTokenInStatementIndex(\PHP_CodeSniffer\Files\File $phpcsFile, int $stackPtr): int|bool
    {
		$previousCurlyBracketPosition = $phpcsFile->findPrevious(
			[
				T_OPEN_CURLY_BRACKET
			],
			$stackPtr - 1
		);
		$weAreInsideClassBody = $previousCurlyBracketPosition !== false;
		if (!$weAreInsideClassBody) {
			return 0;
		}
        $previousPossibleStartIndex = $stackPtr;
        $tokens = $phpcsFile->getTokens();
        $parenCount = 0;
        $firstLineTokenIndex = false;
        $lastTokenFoundWasComma = false;
        $lastTokenFoundWasCloseParenthesis = false;
        $commaEncountered = false;
        $objectOperatorEncountered = false;
        $inArrowFunction = false;
        while ($previousPossibleStartIndex !== false && $firstLineTokenIndex === false) {
            $previousPossibleStartIndex = $phpcsFile->findPrevious(
                [
                    T_FN,
                    T_OPEN_CURLY_BRACKET,
                    T_CLOSE_CURLY_BRACKET,
                    T_SEMICOLON,
                    T_OPEN_PARENTHESIS,
                    T_CLOSE_PARENTHESIS,
                    T_OPEN_SHORT_ARRAY,
                    T_COLON,
                    T_COMMA,
                    T_OBJECT_OPERATOR,
                    T_OPEN_SHORT_ARRAY,
                    T_CLOSE_SHORT_ARRAY,
                    T_FN_ARROW
                ],
                $previousPossibleStartIndex - 1
            );
            if ($previousPossibleStartIndex !== false) {
                $previousPossible = $tokens[$previousPossibleStartIndex];
                $previousPossibleCode = $previousPossible['code'];
                if ($previousPossibleCode == T_FN_ARROW) {
                    $inArrowFunction = true;
                } elseif ($previousPossibleCode == T_CLOSE_PARENTHESIS || $previousPossibleCode == T_CLOSE_SHORT_ARRAY) {
                    $parenCount++;
                    $lastTokenFoundWasCloseParenthesis = true;
                } elseif ($previousPossibleCode == T_OPEN_PARENTHESIS || $previousPossibleCode == T_OPEN_SHORT_ARRAY) {
                    if ($parenCount) {
                        $parenCount--;
                    } else {
                        /**
                         * open bracket found - this must be the beginning of a method call of which our token
                         * is a parameter
                         */
                        if ($objectOperatorEncountered) {
                            /**
                             * the token we're checking the indent on is actually a chained method call
                             * of a parameter
                             */
                            $firstLineTokenIndex = $this->findNextStatementStartIndex(
                                $phpcsFile,
                                $previousPossibleStartIndex + 1);
                        } else {
                            $firstLineTokenIndex = $previousPossibleStartIndex;
                        }
                    }
                } elseif ($previousPossibleCode == T_FN) {
                    $inArrowFunction = false;
                    if (!$parenCount && !$commaEncountered) {
                        $firstLineTokenIndex = $previousPossibleStartIndex;
                    }
                } elseif ($previousPossibleCode == T_CLOSE_CURLY_BRACKET) {
                    if (!$parenCount) {
                        if ($lastTokenFoundWasComma || $lastTokenFoundWasCloseParenthesis) {
                            /**
                             * this is the closing curly brace of a closure param in a method call
                             */
                            $previousPossibleStartIndex = $phpcsFile->findPrevious(
                                T_CLOSURE,
                                $previousPossibleStartIndex - 1);
                        } else {
                            /**
                             * this is the closing curly brace of a control structure
                             */
                            $firstLineTokenIndex = $this->findNextStatementStartIndex(
                                $phpcsFile,
                                $previousPossibleStartIndex + 1);
                        }
                    }
                } elseif ($previousPossibleCode == T_OPEN_CURLY_BRACKET) {
                    if (!$parenCount) {
                        /**
                         * open curly bracket found - this must be the beginning of the method our token's statement
                         * is in OR a match statement
                         */
                        $preCurlyBracketTokenIndex = $phpcsFile->findPrevious(
                            [
                                T_MATCH,
                                T_FUNCTION,
                                T_CLOSURE
                            ],
                            $previousPossibleStartIndex - 1);
                        $preCurlyBracketToken = $tokens[$preCurlyBracketTokenIndex];
                        if (in_array($preCurlyBracketToken['code'], [T_CLOSURE, T_FUNCTION])) {
                            $firstLineTokenIndex = $this->findNextStatementStartIndex(
                                $phpcsFile,
                                $previousPossibleStartIndex + 1);
                        } else {
                            return $this->getFirstTokenInStatementIndex($phpcsFile, $previousPossibleStartIndex);
                        }
                    }
                } elseif ($previousPossibleCode == T_COMMA) {
                    /**
                     * we've encountered a comma which must be separating our statement from a previous statement
                     * e.g. where our token's statement is an argument in a method call, and we've now reached
                     * a previous arg in the same method call. however, if we've encountered a ")" and are currently
                     * in some parens then we must be in a set of args for a param call that constitutes one of this
                     * method's args, so we just want to skip over it
                     */
                    if (!$parenCount) {
                        $lastTokenFoundWasComma = true;
                        $commaEncountered = true;
                    }
                } elseif ($previousPossibleCode == T_OBJECT_OPERATOR) {
                    if (!$commaEncountered) {
                        $objectOperatorEncountered = true;
                    }
                } elseif ($previousPossibleCode != T_COLON || !$inArrowFunction) {
                    if (!$parenCount) {
                        $firstLineTokenIndex = $this->findNextStatementStartIndex(
                            $phpcsFile,
                            $previousPossibleStartIndex + 1);
                    }
                }
                if ($previousPossibleCode != T_COMMA) {
                    $lastTokenFoundWasComma = false;
                }
                if ($previousPossibleCode != T_CLOSE_PARENTHESIS && $previousPossibleCode != T_CLOSE_SHORT_ARRAY) {
                    $lastTokenFoundWasCloseParenthesis = false;
                }
            }
        }

        if ($firstLineTokenIndex) {
            /**
             * we now know the line on which this statement starts, so we need the first token on the line
             */
            $statementFirstTokenIndex = $phpcsFile->findFirstOnLine([T_WHITESPACE], $firstLineTokenIndex, true);

            return $statementFirstTokenIndex;
        }
        
        return false;
    }
    
    /**
     * 
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $stackPtr
     * @return array
     */
    private function findNextStatementStartIndex(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        return $phpcsFile->findNext(
            [
                T_WHITESPACE,
                T_COMMENT,
                T_DOC_COMMENT,
                T_DOC_COMMENT_STAR,
                T_DOC_COMMENT_WHITESPACE,
                T_DOC_COMMENT_TAG,
                T_DOC_COMMENT_OPEN_TAG,
                T_DOC_COMMENT_CLOSE_TAG,
                T_DOC_COMMENT_STRING
            ],
            $stackPtr,
            null,
            true);
    }
    
}
