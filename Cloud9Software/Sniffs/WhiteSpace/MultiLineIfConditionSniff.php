<?php

class Cloud9Software_Sniffs_Whitespace_MultiLineIfConditionSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{
    
    public function register()
    {
        return [
            T_IF
        ];
    }
    
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $closingParenIndex = $this->getClosingParenIndex($phpcsFile, $stackPtr);
        
        $tokens = $phpcsFile->getTokens();
        $if = $tokens[$stackPtr];
        $closingParen = $tokens[$closingParenIndex];
        
        if ($closingParen['line'] != $if['line'] && $closingParen['column'] != $if['column']) {
            $phpcsFile->addError(
                'Closing paren should be on the same column as "if"',
                $stackPtr,
                'MultiLineIfCondition');
        }
    }
    
    /**
     * 
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $stackPtr
     * @return int
     */
    private function getClosingParenIndex(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
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