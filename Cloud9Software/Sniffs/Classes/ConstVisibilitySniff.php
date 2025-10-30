<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\Classes;

final readonly class ConstVisibilitySniff implements \PHP_CodeSniffer\Sniffs\Sniff
{

    #[\Override]
    public function register()
    {
        return [T_CONST];
    }

    #[\Override]
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check if this is a class constant (not a global const or define)
        $classPtr = $phpcsFile->findPrevious([T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM], $stackPtr);
        if ($classPtr === false) {
            // This is a global constant, not a class constant
            return;
        }

        // Check if we're inside a class scope
        $classToken = $tokens[$classPtr];
        if (!isset($classToken['scope_opener']) || !isset($classToken['scope_closer'])) {
            return;
        }

        if ($stackPtr < $classToken['scope_opener'] || $stackPtr > $classToken['scope_closer']) {
            // This const is not inside this class
            return;
        }

        // Look backwards for visibility modifiers (public, protected, private)
        // We need to check tokens immediately before the const keyword
        $hasVisibility = false;
        $prevTokenPtr = $stackPtr - 1;

        // Skip whitespace and comments backwards
        while ($prevTokenPtr > $classToken['scope_opener']) {
            $tokenCode = $tokens[$prevTokenPtr]['code'];

            if ($tokenCode === T_PUBLIC || $tokenCode === T_PROTECTED || $tokenCode === T_PRIVATE) {
                $hasVisibility = true;
                break;
            }

            // If we encounter something that's not whitespace, comment, or visibility modifier,
            // then there's no visibility modifier for this const
            if ($tokenCode !== T_WHITESPACE && $tokenCode !== T_COMMENT && $tokenCode !== T_DOC_COMMENT) {
                break;
            }

            $prevTokenPtr--;
        }

        if (!$hasVisibility) {
            $phpcsFile->addError(
                'Class constant must have a visibility modifier (public, protected, or private)',
                $stackPtr,
                'MissingVisibility'
            );
        }
    }

}

