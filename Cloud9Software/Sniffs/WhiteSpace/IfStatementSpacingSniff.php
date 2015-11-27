<?php

class Cloud9Software_Sniffs_Whitespace_IfStatementSpacingSniff
    implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return [
            T_IF
        ];
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $openParenthesisIndex = $phpcsFile->findNext(
            T_OPEN_PARENTHESIS,
            $stackPtr + 1);
        
        $tokens = $phpcsFile->getTokens();
        $tokenFollowingParenIsWhitespace = $tokens[$openParenthesisIndex + 1]['code'] == T_WHITESPACE;
        $nextNonWhitespaceTokenIndex = $phpcsFile->findNext(
            T_WHITESPACE,
            $openParenthesisIndex + 1,
            null,
            true);
        $nextNonWhitespaceToken = $tokens[$nextNonWhitespaceTokenIndex];
        
        $thisToken = $tokens[$stackPtr];
        $nextNonWhitespaceTokenIsOnSameLine = $nextNonWhitespaceToken['line'] == $thisToken['line'];
        
        if ($tokenFollowingParenIsWhitespace && $nextNonWhitespaceTokenIsOnSameLine) {
            $phpcsFile->addError(
                "Whitespace found before 'if' statement conditions",
                $stackPtr,
                'IfStatementSpacing');
        }
    }
    
}