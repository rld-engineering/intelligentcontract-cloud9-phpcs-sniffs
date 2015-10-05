<?php

class HappyCustomer_Sniffs_ControlStructures_ClosingParenNewlineSniff implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return array(
            T_IF,
            T_WHILE,
            T_FOR,
            T_FOREACH,
            T_ELSEIF
        );
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $closingParenIndex = $this->getClosingParenIndex($phpcsFile, $stackPtr);
        
        if ($closingParenIndex) {
            $thisToken = $tokens[$stackPtr];
            $closingParenToken = $tokens[$closingParenIndex];
            
            if ($closingParenToken['line'] != $thisToken['line']) {
                /**
                 * there shouldn't be anything else before the paren on the line
                 */
                $previousTokenIndex = $phpcsFile->findPrevious(
                    array(T_WHITESPACE),
                    $closingParenIndex - 1,
                    null,
                    true);
                if ($tokens[$previousTokenIndex]['line'] == $closingParenToken['line']) {
                    $phpcsFile->addError(
                        'Closing parenthesis and "{" of a multi-line control structure expression '
                        . 'should be on their own line',
                        $stackPtr);
                }
            }
        }
    }
    
    private function getClosingParenIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $searchTokens = array(
            T_OPEN_PARENTHESIS,
            T_CLOSE_PARENTHESIS
        );
        
        $nextParenIndex = $phpcsFile->findNext($searchTokens, $stackPtr + 1);
        $parenCount = 0;
        
        while (true) {
            $nextParenToken = $tokens[$nextParenIndex];
            
            switch ($nextParenToken['code']) {
                case T_OPEN_PARENTHESIS:
                    $parenCount++;
                    break;
                case T_CLOSE_PARENTHESIS:
                    $parenCount--;
                    
                    if (!$parenCount) {
                        return $nextParenIndex;
                    }
            }
            
            $nextParenIndex = $phpcsFile->findNext($searchTokens, $nextParenIndex + 1);
        }
    }
    
}