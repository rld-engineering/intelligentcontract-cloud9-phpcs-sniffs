<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\Whitespace;
final readonly class ReturnTypeSpaceSniff implements \PHP_CodeSniffer\Sniffs\Sniff
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
        $closingParenIndex = $this->closingParenIndex($phpcsFile, $stackPtr + 1);
        if ($closingParenIndex === -1) {
            return;
        }
        $nextNonWhitespaceTokenIndex = $phpcsFile->findNext(
            [
                T_WHITESPACE
            ],
            $closingParenIndex + 1,
            null,
            true,
        );
        if ($nextNonWhitespaceTokenIndex === false) {
            return;
        }
        $nextNonWhitespaceToken = $tokens[$nextNonWhitespaceTokenIndex];
        if ($nextNonWhitespaceToken['content'] !== ':') {
            return;
        }
        $nextToken = $tokens[$nextNonWhitespaceTokenIndex + 1] ?? [];
        if (!$nextToken) {
            return;
        }
        if (in_array($nextToken['content'], [" ", "\n", "\r"])) {
            return;
        }
        $phpcsFile->addError(
            '":" must be followed by a space',
            $stackPtr,
            'ReturnTypeSpace',
        );
    }

    private function closingParenIndex(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr): int
    {
        $nextParen = $phpcsFile->findNext(
            [
                T_OPEN_PARENTHESIS,
                T_CLOSE_PARENTHESIS
            ],
            $stackPtr,
            null,
            false,
        );
        $tokens = $phpcsFile->getTokens();
        $bracketCounter = 0;
        while ($nextParen) {
            $nextToken = $tokens[$nextParen];
            if ($nextToken['code'] == T_OPEN_PARENTHESIS) {
                $bracketCounter++;
            }
            if ($nextToken['code'] == T_CLOSE_PARENTHESIS) {
                $bracketCounter--;
                if (!$bracketCounter) {
                    return $nextParen;
                }
            }
            $nextParen = $phpcsFile->findNext(
                [
                    T_OPEN_PARENTHESIS,
                    T_CLOSE_PARENTHESIS
                ],
                $nextParen + 1,
                null,
                false,
            );
        }
        return -1;
    }

}
