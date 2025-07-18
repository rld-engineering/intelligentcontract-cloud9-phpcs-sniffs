<?php

declare(strict_types = 1);

class Cloud9Software_Sniffs_Whitespace_ClosingBracketNewLineSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{
    
    public function register()
    {
        return [
            T_CLOSE_PARENTHESIS,
            T_CLOSE_SQUARE_BRACKET,
        ];
	}
	
    private function isThisTheLastBracketTokenOnLine(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr): bool
    {
        $tokens = $phpcsFile->getTokens();
        $bracketCode = $tokens[$stackPtr]['code'];
        $thisTokenLine = $tokens[$stackPtr]['line'];
        $nextTokenIndex = $phpcsFile->findNext(
            T_CLOSE_PARENTHESIS,
            $stackPtr + 1,
        );
        if (!$nextTokenIndex) {
            return true;
        }
        $nextToken = $tokens[$nextTokenIndex];
        return $nextToken['line'] != $thisTokenLine;
    }
    
	public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
	{
        if (!$this->isThisTheLastBracketTokenOnLine($phpcsFile, $stackPtr)) {
            return;
        }
        $numberOfTokensOnThisLine = $this->numberOfTokensOnThisLine(
            $phpcsFile,
            $stackPtr,
            false,
		);
        $numberOfBracketTokensOnThisLine = $this->numberOfTokensOnThisLine(
            $phpcsFile,
            $stackPtr,
            true,
		);
        if ($numberOfTokensOnThisLine === 1) {
            return;
        }
        $numberOfBracketsIsOdd = ($numberOfBracketTokensOnThisLine % 2) != 0;
        if ($numberOfBracketsIsOdd) {
            $phpcsFile->addError(
                "Closing bracket must be on a new line",
                $stackPtr,
                'ClosingBracketNewLine',
            );
        }
    }
	
    private function numberOfTokensOnThisLine(
        \PHP_CodeSniffer\Files\File $phpcsFile,
        int $stackPtr,
        bool $limitToBracketTokens,
    ): int {
        $tokens = $phpcsFile->getTokens();
        $bracketCode = $tokens[$stackPtr]['type'];
        $thisLine = $tokens[$stackPtr]['line'];
        $prevNonWhitespaceTokenIndex = $stackPtr;
        $bracketCount = 1;
        $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(
            T_WHITESPACE,
            $prevNonWhitespaceTokenIndex - 1,
            null,
            true,
        );
        $prevTokenLine = $tokens[$prevNonWhitespaceTokenIndex]['line'];
        while ($prevTokenLine == $thisLine) {
            $prevTokenCode = $tokens[$prevNonWhitespaceTokenIndex]['type'];
            $bracketMatches = $this->doesTokenMatch(
                $prevTokenCode,
                $bracketCode,
                $limitToBracketTokens,
            );
            if ($bracketMatches) {
                $bracketCount++;
            }
            $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(
                T_WHITESPACE,
                $prevNonWhitespaceTokenIndex - 1,
                null,
                true,
            );
            $prevTokenLine = $tokens[$prevNonWhitespaceTokenIndex]['line'];
        }
        return $bracketCount;
    }
    
    private function doesTokenMatch(
        string $prevTokenCode,
        string $bracketCode,
        bool $limitToBracketTokens,
    ): bool {
        if (!$limitToBracketTokens) {
            return true;
        }
        return match ($bracketCode) {
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
            default => throw new \Exception($bracketCode),
        };
    }
	
}

