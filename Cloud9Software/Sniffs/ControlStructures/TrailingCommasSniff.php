<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\ControlStructures;

final readonly class TrailingCommasSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public const CODE_MISSING_TRAILING_COMMA = 'MissingTrailingComma';
    public const CODE_UNEXPECTED_TRAILING_COMMA = 'UnexpectedTrailingComma';

    public function register()
    {
        return [
            T_OPEN_PARENTHESIS,
            T_OPEN_SHORT_ARRAY,
            T_MATCH,
        ];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];

        // Handle match statements
        if ($token['code'] === T_MATCH) {
            $this->processMatch($phpcsFile, $stackPtr);
            return;
        }

        $isArray = ($token['code'] === T_OPEN_SHORT_ARRAY);

        // Determine closer pointer.
        $closerPtr = $isArray
            ? ($token['bracket_closer'] ?? null)
            : ($token['parenthesis_closer'] ?? null);

        if ($closerPtr === null) {
            return;
        }

        $prevPtr = $phpcsFile->findPrevious(\PHP_CodeSniffer\Util\Tokens::$emptyTokens, $stackPtr - 1, null, true);
        if ($prevPtr === false) {
            return;
        }
        $prevCode = $tokens[$prevPtr]['code'];

        $controlStructureCodes = [
            T_IF, T_ELSEIF, T_ELSE, T_FOR, T_FOREACH, T_WHILE,
            T_SWITCH, T_CATCH, T_MATCH, T_DECLARE,
        ];
        if (in_array($prevCode, $controlStructureCodes, true)) {
            return;
        }

        $isList = false;
        if ($isArray) {
            $isList = true;
        }

        if (!$isArray && isset($token['parenthesis_owner'])) {
            $ownerCode = $tokens[$token['parenthesis_owner']]['code'];
            if (in_array($ownerCode, [T_FUNCTION, T_FN, T_CLOSURE], true)) {
                $isList = true; // parameter list
            }
        }

        if (!$isArray && !$isList) {
            $callLikePrevCodes = [
                T_STRING, T_VARIABLE, T_CLOSE_PARENTHESIS, T_STATIC, T_SELF, T_PARENT,
                T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR, T_PAAMAYIM_NEKUDOTAYIM,
                T_NEW, T_CLOSE_SQUARE_BRACKET,
            ];
            if (in_array($prevCode, $callLikePrevCodes, true)) {
                $isList = true; // call / instantiation argument list
            }
        }

        if (!$isList) {
            return; // grouping parenthesis, do not enforce
        }

        // Find last non-empty (excluding whitespace & comments) before closer.
        $searchPtr = $closerPtr - 1;
        while ($searchPtr > $stackPtr) {
            $code = $tokens[$searchPtr]['code'];
            if (!in_array($code, \PHP_CodeSniffer\Util\Tokens::$emptyTokens, true)
                && $code !== T_COMMENT
                && $code !== T_DOC_COMMENT_CLOSE_TAG
                && $code !== T_DOC_COMMENT_WHITESPACE
                && $code !== T_DOC_COMMENT_STAR
                && $code !== T_DOC_COMMENT_STRING
                && $code !== T_DOC_COMMENT_OPEN_TAG
                && $code !== T_DOC_COMMENT_TAG
            ) {
                break;
            }
            $searchPtr--;
        }

        // Empty list.
        if ($searchPtr <= $stackPtr) {
            return; // empty () or []
        }

        $lastMeaningfulPtr = $searchPtr;
        $hasTrailingComma  = ($tokens[$lastMeaningfulPtr]['code'] === T_COMMA);

        // Determine last element ptr (exclude comma if present).
        $lastElementPtr = $hasTrailingComma
            ? $phpcsFile->findPrevious(\PHP_CodeSniffer\Util\Tokens::$emptyTokens, $lastMeaningfulPtr - 1, null, true)
            : $lastMeaningfulPtr;

        if ($lastElementPtr === false) {
            return;
        }

        $lastElementLine = $tokens[$lastElementPtr]['line'];
        $closerLine = $tokens[$closerPtr]['line'];

        $shouldHaveTrailingComma = ($lastElementLine !== $closerLine);

        // Rewritten without else/elseif.
        if ($shouldHaveTrailingComma && !$hasTrailingComma) {
            $fix = $phpcsFile->addFixableError(
                'Multi-line list must end with a trailing comma.',
                $lastElementPtr,
                self::CODE_MISSING_TRAILING_COMMA
            );
            if ($fix) {
                $phpcsFile->fixer->addContent($lastElementPtr, ',');
            }
        }

        if (!$shouldHaveTrailingComma && $hasTrailingComma) {
            $fix = $phpcsFile->addFixableError(
                'Single-line list must not have a trailing comma.',
                $lastMeaningfulPtr,
                self::CODE_UNEXPECTED_TRAILING_COMMA
            );
            if ($fix) {
                $phpcsFile->fixer->replaceToken($lastMeaningfulPtr, '');
            }
        }
    }

    private function processMatch(\PHP_CodeSniffer\Files\File $phpcsFile, int $matchPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        // Find the opening brace of the match body
        $openBracePtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $matchPtr + 1);
        if ($openBracePtr === false || !isset($tokens[$openBracePtr]['bracket_closer'])) {
            return;
        }

        $closeBracePtr = $tokens[$openBracePtr]['bracket_closer'];

        // Find last non-empty token before closing brace
        $searchPtr = $closeBracePtr - 1;
        while ($searchPtr > $openBracePtr) {
            $code = $tokens[$searchPtr]['code'];
            if (!in_array($code, \PHP_CodeSniffer\Util\Tokens::$emptyTokens, true)
                && $code !== T_COMMENT
                && $code !== T_DOC_COMMENT_CLOSE_TAG
            ) {
                break;
            }
            $searchPtr--;
        }

        if ($searchPtr <= $openBracePtr) {
            return; // Empty match
        }

        $lastMeaningfulPtr = $searchPtr;
        $hasTrailingComma  = ($tokens[$lastMeaningfulPtr]['code'] === T_COMMA);

        // Find the last element (before comma if present)
        $lastElementPtr = $hasTrailingComma
            ? $phpcsFile->findPrevious(\PHP_CodeSniffer\Util\Tokens::$emptyTokens, $lastMeaningfulPtr - 1, null, true)
            : $lastMeaningfulPtr;

        if ($lastElementPtr === false) {
            return;
        }

        $lastElementLine = $tokens[$lastElementPtr]['line'];
        $closeBraceLine = $tokens[$closeBracePtr]['line'];

        $shouldHaveTrailingComma = ($lastElementLine !== $closeBraceLine);

        if ($shouldHaveTrailingComma && !$hasTrailingComma) {
            $fix = $phpcsFile->addFixableError(
                'Multi-line match statement must end with a trailing comma.',
                $lastElementPtr,
                self::CODE_MISSING_TRAILING_COMMA
            );
            if ($fix) {
                $phpcsFile->fixer->addContent($lastElementPtr, ',');
            }
        }

        if (!$shouldHaveTrailingComma && $hasTrailingComma) {
            $fix = $phpcsFile->addFixableError(
                'Single-line match statement must not have a trailing comma.',
                $lastMeaningfulPtr,
                self::CODE_UNEXPECTED_TRAILING_COMMA
            );
            if ($fix) {
                $phpcsFile->fixer->replaceToken($lastMeaningfulPtr, '');
            }
        }
    }

}
