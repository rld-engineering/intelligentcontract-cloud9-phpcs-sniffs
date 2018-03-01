<?php

class Cloud9Software_Sniffs_Whitespace_ScopeIndentSniff implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return [
            T_STRING,
            T_VARIABLE
        ];
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $firstTokenInStatementIndex = $this->getFirstTokenInStatementIndex($phpcsFile, $stackPtr);
        $thisTokenIsTheStartOfAStatement = $firstTokenInStatementIndex == $stackPtr;
        
        if ($thisTokenIsTheStartOfAStatement) {
            $scopeStartIndex = $this->findScopeStartIndex($phpcsFile, $stackPtr);
            
            if ($scopeStartIndex !== false) {
                $scopeStartToken = $tokens[$scopeStartIndex];
                
                $expectedIndent = $scopeStartToken['column'] + 4;
                $actualIndent = $tokens[$stackPtr]['column'];
                
                if ($expectedIndent != $actualIndent) {
                    $phpcsFile->addError(
                        "Indent incorrect; expected " . ($expectedIndent - 1)
                        . ', found ' . ($actualIndent - 1),
                        $stackPtr,
                        'ScopeIndent');
                }
            }
        }
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return int
     */
    private function findScopeStartIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $previousPossibleStartIndex = $stackPtr;
        $scopeStartIndex = false;
        $tokens = $phpcsFile->getTokens();
        $curlyParenCount = 1;
        $parenCount = 0;
        
        $structs = [
            T_WHILE,
            T_FOR,
            T_FOREACH
        ];
        
        while ($previousPossibleStartIndex !== false && $scopeStartIndex === false) {
            $previousPossibleStartIndex = $phpcsFile->findPrevious(
                [
                    T_OPEN_CURLY_BRACKET,
                    T_CLOSE_CURLY_BRACKET,
                    T_CASE,
                    T_DEFAULT,
                    T_IF,
                    T_FOR,
                    T_FOREACH,
                    T_DO,
                    T_FUNCTION,
                    T_WHILE,
                    T_TRY,
                    T_OPEN_PARENTHESIS,
                    T_CLOSE_PARENTHESIS,
                    T_FUNCTION,
                    T_CLOSURE
                ],
                $previousPossibleStartIndex - 1);
            
            if ($previousPossibleStartIndex !== false) {
                $previousPossible = $tokens[$previousPossibleStartIndex];
                $previousPossibleCode = $previousPossible['code'];
                
                switch ($previousPossibleCode) {
                    case T_CLOSE_CURLY_BRACKET:
                        $curlyParenCount++;
                        break;
                    case T_OPEN_CURLY_BRACKET:
                        $curlyParenCount--;
                        break;
                    case T_CLOSE_PARENTHESIS:
                        $parenCount++;
                        break;
                    case T_OPEN_PARENTHESIS:
                        $parenCount--;
                        break;
                    case T_CASE:
                        if ($curlyParenCount == 1) {
                            $scopeStartIndex = $previousPossibleStartIndex;
                        }
                        break;
                    default:
                        if (in_array($previousPossibleCode, $structs) && $parenCount) {
                            /**
                             * e.g. where token is:
                             * for ($tokenIsInHere)
                             */
                            $scopeStartIndex = $previousPossibleStartIndex;
                        } elseif (!$curlyParenCount) {
                            $scopeStartIndex = $previousPossibleStartIndex;
                        }
                }
            }
        }
        
        if ($scopeStartIndex !== false) {
            $scopeStartFirstTokenIndex = $phpcsFile->findFirstOnLine([T_WHITESPACE], $scopeStartIndex, true);
            
            return $scopeStartFirstTokenIndex;
        }
        
        return false;
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return int
     */
    private function getFirstTokenInStatementIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $previousPossibleStartIndex = $stackPtr;
        $tokens = $phpcsFile->getTokens();
        $parenCount = 0;
        $curlyParenCount = 0;
        $firstLineTokenIndex = false;
        $lastTokenFoundWasComma = false;
        $commaEncountered = false;
        $objectOperatorEncountered = false;
        
        while ($previousPossibleStartIndex !== false && $firstLineTokenIndex === false) {
            $previousPossibleStartIndex = $phpcsFile->findPrevious(
                [
                    T_OPEN_CURLY_BRACKET,
                    T_CLOSE_CURLY_BRACKET,
                    T_SEMICOLON,
                    T_OPEN_PARENTHESIS,
                    T_CLOSE_PARENTHESIS,
                    T_COLON,
                    T_COMMA,
                    T_OBJECT_OPERATOR
                ],
                $previousPossibleStartIndex - 1);
            
            if ($previousPossibleStartIndex !== false) {
                $previousPossible = $tokens[$previousPossibleStartIndex];
                $previousPossibleCode = $previousPossible['code'];
                
                if ($previousPossibleCode == T_CLOSE_PARENTHESIS) {
                    $parenCount++;
                } elseif ($previousPossibleCode == T_OPEN_PARENTHESIS) {
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
                } elseif ($previousPossibleCode == T_CLOSE_CURLY_BRACKET) {
                    if ($lastTokenFoundWasComma) {
                        /**
                         * this is the closing curly brace of a closure param in a method call
                         */
                        $curlyParenCount++;
                    } else {
                        /**
                         * this is the closing curly brace of a control structure
                         */
                        $firstLineTokenIndex = $this->findNextStatementStartIndex(
                            $phpcsFile,
                            $previousPossibleStartIndex + 1);
                    }
                } elseif ($previousPossibleCode == T_OPEN_CURLY_BRACKET) {
                    if ($curlyParenCount) {
                        $curlyParenCount--;
                    } else {
                        /**
                         * open curly bracket found - this must be the beginning of the method our token's statement
                         * is in
                         */
                        $firstLineTokenIndex = $this->findNextStatementStartIndex(
                            $phpcsFile,
                            $previousPossibleStartIndex + 1);
                    }
                } elseif ($previousPossibleCode == T_COMMA) {
                    $lastTokenFoundWasComma = true;
                    $commaEncountered = true;
                } elseif ($previousPossibleCode == T_OBJECT_OPERATOR) {
                    if (!$commaEncountered) {
                        $objectOperatorEncountered = true;
                    }
                } else {
                    if (!$parenCount && !$curlyParenCount) {
                        $firstLineTokenIndex = $this->findNextStatementStartIndex(
                            $phpcsFile,
                            $previousPossibleStartIndex + 1);
                    }
                }
                
                if ($previousPossibleCode != T_COMMA) {
                    $lastTokenFoundWasComma = false;
                }
            }
        }

        if ($firstLineTokenIndex !== false) {
            /**
             * we now know the line on which this statement starts, so we need the first token on the line
             */
            $statementFirstTokenIndex = $phpcsFile->findFirstOnLine([T_WHITESPACE], $firstLineTokenIndex, true);

            $return = $statementFirstTokenIndex;
        } else {
            $return = false;
        }
        
        return $return;
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return array
     */
    private function findNextStatementStartIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $phpcsFile->findNext(
            [
                T_WHITESPACE,
                T_COMMENT,
                T_DOC_COMMENT
            ],
            $stackPtr,
            null,
            true);
    }
    
}