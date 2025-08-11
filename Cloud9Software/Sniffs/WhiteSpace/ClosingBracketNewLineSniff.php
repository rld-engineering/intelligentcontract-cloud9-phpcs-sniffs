<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\Whitespace;

final readonly class ClosingBracketNewLineSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{

    public function register()
    {
        return [
            T_CLOSE_PARENTHESIS,
            T_CLOSE_SQUARE_BRACKET,
        ];
    }

    private function isThisTheLastBracketTokenOnLine(
        \PHP_CodeSniffer\Files\File $phpcsFile,
        int $stackPtr,
    ): bool {
        $tokens = $phpcsFile->getTokens();
        $thisTokenLine = $tokens[$stackPtr]['line'];
        $nextTokenIndex = $phpcsFile->findNext(
            $tokens[$stackPtr]['code'],
            $stackPtr + 1,
        );
        if (!$nextTokenIndex) {
            return true;
        }
        $nextToken = $tokens[$nextTokenIndex];
        return $nextToken['line'] != $thisTokenLine;
    }

    private function isFirstTokenOnLineIf(
        \PHP_CodeSniffer\Files\File $phpcsFile,
        int $stackPtr,
    ): bool {
        $tokens = $phpcsFile->getTokens();
        $thisLine = $tokens[$stackPtr]['line'];
        $prevIfToken = $phpcsFile->findPrevious(
            T_IF,
            $stackPtr - 1,
        );
        if ($prevIfToken === false) {
            return false;
        }
        return $tokens[$prevIfToken]['line'] == $thisLine;
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        if ($this->isFirstTokenOnLineIf($phpcsFile, $stackPtr)) {
            return;
        }
        if (!$this->isThisTheLastBracketTokenOnLine($phpcsFile, $stackPtr)) {
            return;
        }
        if (!$this->areThereAnyTokensBeforeThisTokenOnThisLine($phpcsFile, $stackPtr)) {
            return;
        }
        $isLastBracketBalanced = $this->isLastBracketBalanced(
            $phpcsFile,
            $stackPtr,
        );
        if (!$isLastBracketBalanced) {
            $phpcsFile->addError(
                "Closing bracket must be on a new line",
                $stackPtr,
                'ClosingBracketNewLine',
            );
        }
    }

    private function areThereAnyTokensBeforeThisTokenOnThisLine(
        \PHP_CodeSniffer\Files\File $phpcsFile,
        int $stackPtr,
    ): bool {
        $tokens = $phpcsFile->getTokens();
        $thisLine = $tokens[$stackPtr]['line'];
        $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(
            T_WHITESPACE,
            $stackPtr - 1,
            null,
            true,
        );
        if ($prevNonWhitespaceTokenIndex === false) {
            return false;
        }
        return $tokens[$prevNonWhitespaceTokenIndex]['line'] == $thisLine;
    }

    private function isLastBracketBalanced(
        \PHP_CodeSniffer\Files\File $phpcsFile,
        int $stackPtr,
    ): bool {
        $tokens = $phpcsFile->getTokens();
        $tokenCode = $tokens[$stackPtr]['type'];
        $thisLine = $tokens[$stackPtr]['line'];
        $prevNonWhitespaceTokenIndex = $stackPtr;
        $closeTokenCount = 1;
        $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(
            T_WHITESPACE,
            $prevNonWhitespaceTokenIndex - 1,
            null,
            true,
        );
        $prevTokenLine = $tokens[$prevNonWhitespaceTokenIndex]['line'];
        while ($prevTokenLine == $thisLine) {
            $prevTokenCode = $tokens[$prevNonWhitespaceTokenIndex]['type'];
            $tokenMatches = $this->doesTokenMatch(
                $prevTokenCode,
                $tokenCode,
            );
            if ($tokenMatches) {
                if (
                    in_array(
                        $prevTokenCode,
                        [
                            'T_CLOSE_PARENTHESIS',
                            'T_CLOSE_SQUARE_BRACKET',
                        ],
                    )
                ) {
                    $closeTokenCount++;
                }
                if (
                    in_array(
                        $prevTokenCode,
                        [
                            'T_OPEN_PARENTHESIS',
                            'T_OPEN_SQUARE_BRACKET',
                        ],
                    )
                ) {
                    $closeTokenCount--;
                    if (!$closeTokenCount) {
                        return true;
                    }
                }
            }
            $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(
                T_WHITESPACE,
                $prevNonWhitespaceTokenIndex - 1,
                null,
                true,
            );
            $prevTokenLine = $tokens[$prevNonWhitespaceTokenIndex]['line'];
        }
        return $closeTokenCount === 0;
    }

    private function doesTokenMatch(
        string $prevTokenCode,
        string $tokenCode,
    ): bool {
        return match ($tokenCode) {
            'T_CLOSE_PARENTHESIS' => in_array(
                $prevTokenCode,
                [
                    'T_OPEN_PARENTHESIS',
                    'T_CLOSE_PARENTHESIS',
                ],
            ),
            'T_CLOSE_SQUARE_BRACKET' => in_array(
                $prevTokenCode,
                [
                    'T_OPEN_SQUARE_BRACKET',
                    'T_CLOSE_SQUARE_BRACKET',
                ],
            ),
            default => throw new \Exception($tokenCode),
        };
    }

}

