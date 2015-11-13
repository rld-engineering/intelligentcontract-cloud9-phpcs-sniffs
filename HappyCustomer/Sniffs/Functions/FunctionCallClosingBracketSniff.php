<?php

class HappyCustomer_Sniffs_Functions_FunctionCallClosingBracketSniff implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return array(
            T_OBJECT_OPERATOR
        );
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if (!$this->isThisAMethodCall($phpcsFile, $stackPtr)) {
            return;
        }
        
        $closingParenIndex = $this->getMethodCallClosingParenIndex($phpcsFile, $stackPtr);
        
        $tokens = $phpcsFile->getTokens();
        $closingParen = $tokens[$closingParenIndex];
        
        $lineNumberOfClosingParen = $closingParen['line'];
        
        $indexOfTokenBeforeClosingParen = $phpcsFile->findPrevious(
            T_WHITESPACE,
            $closingParenIndex - 1,
            null,
            true);
        $tokenBeforeClosingParen = $tokens[$indexOfTokenBeforeClosingParen];
        
        if ($tokenBeforeClosingParen['line'] != $lineNumberOfClosingParen) {
            $phpcsFile->addError(
                'Closing parenthesis of a function call must not be on a new line',
                $stackPtr,
                'FunctionCallClosingBracket');
        }
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return bool
     */
    private function isThisAMethodCall(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $methodNameTokenIndex = $phpcsFile->findNext(
            T_WHITESPACE,
            $stackPtr + 1,
            null,
            true);
        $openParenIndex = $phpcsFile->findNext(
            T_WHITESPACE,
            $methodNameTokenIndex + 1,
            null,
            true);
        $openParen = $tokens[$openParenIndex];
        
        return $openParen['code'] == T_OPEN_PARENTHESIS;
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return int
     */
    private function getMethodCallClosingParenIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $parenTypes = [
            T_OPEN_PARENTHESIS,
            T_CLOSE_PARENTHESIS
        ];
        
        $parenCount = 0;
        
        $nextParenIndex = $phpcsFile->findNext(
            $parenTypes,
            $stackPtr + 1);
        
        while ($nextParenIndex) {
            $nextParen = $tokens[$nextParenIndex];

            switch ($nextParen['code']) {
                case T_OPEN_PARENTHESIS:
                    $parenCount++;
                    break;
                case T_CLOSE_PARENTHESIS:
                    $parenCount--;
                    break;
            }

            if (!$parenCount) {
                return $nextParenIndex;
            }

            $nextParenIndex = $phpcsFile->findNext(
                $parenTypes,
                $nextParenIndex + 1);
        }
    }
    
}