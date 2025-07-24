<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\Whitespace;
final readonly class IfStatementSpacingSniff
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