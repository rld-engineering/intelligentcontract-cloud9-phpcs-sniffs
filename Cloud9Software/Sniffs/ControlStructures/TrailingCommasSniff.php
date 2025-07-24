<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\ControlStructures;

final readonly class TrailingCommasSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{

    public function register()
    {
        return [
            T_CLOSE_PARENTHESIS,
            T_CLOSE_SQUARE_BRACKET,
        ];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $thisToken = $tokens[$stackPtr];
        $thisLine = $thisToken['line'];
        $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(
            T_WHITESPACE,
            $stackPtr - 1,
            null,
            true,
        );
        $prevToken = $tokens[$prevNonWhitespaceTokenIndex];
        if ($prevToken['line'] != $thisLine && $prevToken['code'] != T_COMMA) {
            $phpcsFile->addError(
                "Last element of a multi-line comma separated list must have a trailing comma",
                $stackPtr,
                'TrailingCommas',
            );
        }
    }

}
