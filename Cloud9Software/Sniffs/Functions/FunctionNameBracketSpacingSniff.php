<?php

declare(strict_types = 1);

class Cloud9Software_Sniffs_Functions_FunctionNameBracketSpacingSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    
    public function register()
    {
        return [
            T_FUNCTION
        ];
    }
    
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $openingParenIndex = $phpcsFile->findNext([T_OPEN_PARENTHESIS], $stackPtr);
        
        if ($openingParenIndex) {
            $previousToken = $tokens[$openingParenIndex - 1];
            if ($previousToken['code'] != T_STRING) {
                $phpcsFile->addError(
                    'Space found between function name and opening parenthesis',
                    $stackPtr,
                    'SpaceFound');
            }
        }
    }
    
}