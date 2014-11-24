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
    private function _isThisLastTokenOnLine(array $tokens, $stackPtr)
    {
        $currentLineNumber = $tokens[$stackPtr]['line'];
        
        return count($tokens) == $stackPtr + 1
            || $tokens[$stackPtr + 1]['line'] > $currentLineNumber;
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        if ($this->_isThisLastTokenOnLine($tokens, $stackPtr)) {
            $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
            $currentLineNumber = $tokens[$stackPtr]['line'];
            
            $error = false;
            
            if ($prevNonWhitespaceTokenIndex !== false) {
                /**
                 * we must have at least 2 contiguous newlines if prev non whitespace token
                 * is more than 2 lines back
                 */
                $prevNonWhitespaceToken = $tokens[$prevNonWhitespaceTokenIndex];
                
                if ($currentLineNumber - $prevNonWhitespaceToken['line'] > 1) {
                    $error = true;
                }
            } elseif ($currentLineNumber > $tokens[0]['line']) {
                /**
                 * no previous token and this isn't the first line
                 */
                $error = true;
            }
            
            if ($error) {
                $phpcsFile->addError("Contiguous blank lines found", $stackPtr, 'ContiguousNewlines');
            }
        }
    }
    
}
