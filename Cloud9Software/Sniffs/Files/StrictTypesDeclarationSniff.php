<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\Files;

final readonly class StrictTypesDeclarationSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{

    #[\Override]
    public function register()
    {
        return [T_OPEN_TAG];
    }

    #[\Override]
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        // Only act on the first PHP open tag.
        $prevOpen = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
        if ($prevOpen !== false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Determine upper bound to search (before first structure).
        $structureTokens = [
            T_NAMESPACE,
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
            T_FUNCTION,
        ];
        $endSearch = $phpcsFile->findNext($structureTokens, $stackPtr + 1);
        if ($endSearch === false) {
            $endSearch = $phpcsFile->numTokens - 1;
        }

        $declarePtr = $phpcsFile->findNext(T_DECLARE, $stackPtr + 1, $endSearch);
        if ($declarePtr === false) {
            $phpcsFile->addError(
                'Missing strict_types declaration. Add: declare(strict_types=1);',
                $stackPtr,
                'MissingStrictTypes'
            );
            return;
        }

        // Find the parentheses following declare.
        $openParen = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $declarePtr + 1, null, false, null, true);
        if ($openParen === false) {
            $phpcsFile->addError(
                'Malformed declare statement; expected parentheses with strict_types=1.',
                $declarePtr,
                'MalformedDeclare'
            );
            return;
        }

        $closeParen = $tokens[$openParen]['parenthesis_closer'] ?? null;
        if ($closeParen === null) {
            $phpcsFile->addError(
                'Malformed declare statement; missing closing parenthesis.',
                $declarePtr,
                'MalformedDeclare'
            );
            return;
        }

        $declareContent = $phpcsFile->getTokensAsString($openParen + 1, $closeParen - $openParen - 1);

        if (!preg_match('/\bstrict_types\s*=\s*1\b/', $declareContent)) {
            $phpcsFile->addError(
                'strict_types=1 not found in declare statement at top of file.',
                $declarePtr,
                'StrictTypesNotEnabled'
            );
        }
    }

}