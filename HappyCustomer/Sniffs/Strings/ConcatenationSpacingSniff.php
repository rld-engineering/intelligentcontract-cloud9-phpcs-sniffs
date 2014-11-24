<?php

class HappyCustomer_Sniffs_Strings_ConcatenationSpacingSniff
    implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return array(T_STRING_CONCAT);
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        /**
         * make sure the tokens immediately following and preceding this one are whitespace
         */
        if ($tokens[$stackPtr - 1]['code'] != T_WHITESPACE || $tokens[$stackPtr + 1]['code'] != T_WHITESPACE) {
            $phpcsFile->addError(
                'Non-whitespace character found adjacent to string concat operator',
                $stackPtr,
                'StringConcatNoSpace');
        }
    }
    
}