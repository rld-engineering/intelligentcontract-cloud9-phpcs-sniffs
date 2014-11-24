<?php

class HappyCustomer_Sniffs_Whitespace_MultiLineStatementIndentSniff
    implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return array(
            T_OBJECT_OPERATOR,
            T_STRING,
            T_VARIABLE,
            T_CONSTANT_ENCAPSED_STRING,
            T_ARRAY,
            T_ARRAY_HINT
        );
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $firstTokenOnLineIndex = $phpcsFile->findFirstOnLine(array(T_WHITESPACE), $stackPtr, true);
        $thisIsFirstTokenOnLine = ($firstTokenOnLineIndex == $stackPtr);
        
        $previousTokenIsString = $stackPtr && $tokens[$stackPtr - 1]['code'] == T_CONSTANT_ENCAPSED_STRING;
        $thisTokenIsString = $tokens[$stackPtr]['code'] == T_CONSTANT_ENCAPSED_STRING;
        $thisIsContinuationOfMultiLineString = $thisTokenIsString && $previousTokenIsString;
        
        if ($thisIsFirstTokenOnLine && !$thisIsContinuationOfMultiLineString) {
            $statementBeginningIndex = $this->_getFirstTokenInStatementIndex($phpcsFile, $stackPtr);
            
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
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return int
     */
    private function _getFirstTokenInStatementIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
                array(
                    T_OPEN_CURLY_BRACKET,
                    T_CLOSE_CURLY_BRACKET,
                    T_SEMICOLON,
                    T_OPEN_PARENTHESIS,
                    T_CLOSE_PARENTHESIS,
                    T_COLON,
                    T_COMMA,
                    T_OBJECT_OPERATOR
                ),
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
                            $firstLineTokenIndex = $this->_findNextStatementStartIndex(
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
                        $previousPossibleStartIndex = $phpcsFile->findPrevious(
                            T_CLOSURE,
                            $previousPossibleStartIndex - 1);
                    } else {
                        /**
                         * this is the closing curly brace of a control structure
                         */
                        $firstLineTokenIndex = $this->_findNextStatementStartIndex(
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
                        $firstLineTokenIndex = $this->_findNextStatementStartIndex(
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
                        $firstLineTokenIndex = $this->_findNextStatementStartIndex(
                            $phpcsFile,
                            $previousPossibleStartIndex + 1);
                    }
                }
                
                if ($previousPossibleCode != T_COMMA) {
                    $lastTokenFoundWasComma = false;
                }
            }
        }

        if ($firstLineTokenIndex) {
            /**
             * we now know the line on which this statement starts, so we need the first token on the line
             */
            $statementFirstTokenIndex = $phpcsFile->findFirstOnLine(array(T_WHITESPACE), $firstLineTokenIndex, true);

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
    private function _findNextStatementStartIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $phpcsFile->findNext(
            array(
                T_WHITESPACE,
                T_COMMENT,
                T_DOC_COMMENT
            ),
            $stackPtr,
            null,
            true);
    }
    
}