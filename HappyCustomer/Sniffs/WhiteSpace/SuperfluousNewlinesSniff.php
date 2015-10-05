<?php

class HappyCustomer_Sniffs_Whitespace_SuperfluousNewlinesSniff
    implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return array(
            T_OPEN_CURLY_BRACKET,
            T_CLOSE_CURLY_BRACKET
        );
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $currentToken = $tokens[$stackPtr];
        
        if ($currentToken['code'] == T_OPEN_CURLY_BRACKET) {
            $nextNonWhitespaceIndex = $phpcsFile->findNext(array(T_WHITESPACE), $stackPtr + 1, null, true);
        } else {
            $nextNonWhitespaceIndex = $phpcsFile->findPrevious(array(T_WHITESPACE), $stackPtr - 1, null, true);
        }
        
        if ($this->tokenIndexIsInsideFunction($phpcsFile, $nextNonWhitespaceIndex)) {
            $nextNonWhitespaceToken = $tokens[$nextNonWhitespaceIndex];
            
            if (abs($nextNonWhitespaceToken['line'] - $currentToken['line']) > 1) {
                $phpcsFile->addError(
                    "Superfluous newlines found before/after scope start/end",
                    $stackPtr,
                    'SuperfluousNewlines');
            }
        }
    }
    
    private function tokenIndexIsInsideFunction(PHP_CodeSniffer_File $phpcsFile, $index)
    {
        $tokens = $phpcsFile->getTokens();
        
        $tokenIndex = $phpcsFile->findPrevious(
            array(
                T_FUNCTION,
                T_CLOSE_CURLY_BRACKET,
                T_OPEN_CURLY_BRACKET
            ),
            $index);
        
        $bracketCount = 0;
        while ($tokenIndex) {
            $token = $tokens[$tokenIndex];
            
            switch ($token['code']) {
                case T_CLOSE_CURLY_BRACKET:
                    $bracketCount++;
                    break;
                case T_OPEN_CURLY_BRACKET:
                    $bracketCount--;
                    break;
                case T_FUNCTION:
                    if ($bracketCount < 0) {
                        return true;
                    }
                    break;
            }

            $tokenIndex = $phpcsFile->findPrevious(
                array(
                    T_FUNCTION,
                    T_CLOSE_CURLY_BRACKET,
                    T_OPEN_CURLY_BRACKET
                ),
                $tokenIndex - 1);
        }
        
        return false;
    }
    
}