<?php

/**
 * This is different from the Squiz superfluous whitespace sniff in that it detects contiguous newlines outside
 * of functions as well as inside them
 */

class HappyCustomer_Sniffs_Whitespace_DisallowContiguousNewlinesSniff
    implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return array(T_WHITESPACE);
    }
    
    /**
     * 
     * @param array $tokens
     * @param int $stackPtr
     * @return bool
     */
    private function isThisTheLastTokenOnLine(array $tokens, $stackPtr)
    {
        $currentLineNumber = $tokens[$stackPtr]['line'];
        
        return count($tokens) == $stackPtr + 1
            || $tokens[$stackPtr + 1]['line'] > $currentLineNumber;
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        if (!$this->isThisTheLastTokenOnLine($tokens, $stackPtr)) {
            return;
        }
        
        $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
        $currentLineNumber = $tokens[$stackPtr]['line'];

        $isThereANonWhiteSpaceTokenBeforeThisOne = $prevNonWhitespaceTokenIndex !== false;
        
        if ($isThereANonWhiteSpaceTokenBeforeThisOne) {
            $prevNonWhitespaceToken = $tokens[$prevNonWhitespaceTokenIndex];
            
            $numberOfLinesBetweenLastNonWhiteSpaceTokenAndThisOne
                = $currentLineNumber - $prevNonWhitespaceToken['line'];

            if ($numberOfLinesBetweenLastNonWhiteSpaceTokenAndThisOne > 1) {
                $phpcsFile->addError("Contiguous blank lines found", $stackPtr, 'ContiguousNewlines');
            }
            
            return;
        } 
        
        if ($currentLineNumber > $tokens[0]['line']) {
            /**
             * no previous token and this isn't the first line
             */
            $phpcsFile->addError("Contiguous blank lines found", $stackPtr, 'ContiguousNewlines');
        }
    }
    
}
