<?php

declare(strict_types = 1);

final readonly class Cloud9Software_Sniffs_Whitespace_ArrowFunctionBracketspaceSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    
    public function register()
    {
        return [
            T_FN
        ];
    }
    
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $nextToken = $tokens[$stackPtr + 1] ?? [];
        if (!$nextToken) {
            return;
        }
        if ($nextToken['content'] === ' ') {
            return;
        }
        $phpcsFile->addError(
            '"fn" must be followed by a space',
            $stackPtr,
            'ArrayFunctionBracketSpace'
        );
    }
    
}
