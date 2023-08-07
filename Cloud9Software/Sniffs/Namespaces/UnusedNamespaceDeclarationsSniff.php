<?php

declare(strict_types = 1);

class Cloud9Software_Sniffs_Namespaces_UnusedNamespaceDeclarationsSniff
    implements \PHP_CodeSniffer\Sniffs\Sniff
{
    
    public function register()
    {
        return [T_USE];
    }
    
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        if ($this->isUseDeclaration($phpcsFile, $stackPtr)) {
            $endOfDeclarationIndex = $phpcsFile->findNext([T_COMMA, T_SEMICOLON], $stackPtr + 1);
            
            $endOfUseDeclarationReached = false;
            
            while (!$endOfUseDeclarationReached) {
                /**
                 * find the actual alias
                 */
                $aliasIndex = $phpcsFile->findPrevious(T_STRING, $endOfDeclarationIndex);

                $alias = $tokens[$aliasIndex]['content'];

                $nextAliasInstanceIndex = $phpcsFile->findNext(T_STRING, 0, null, false, $alias);

                $namespaceIsUsed = false;

                while (!$namespaceIsUsed && $nextAliasInstanceIndex !== false) {
                    if ($nextAliasInstanceIndex != $aliasIndex) {
                        /**
                         * is this token referring to the alias?
                         */
                        $nextNonWhitespaceTokenIndex = $phpcsFile->findNext(
                            T_WHITESPACE,
                            $nextAliasInstanceIndex + 1,
                            null,
                            true);
                        $prevNonWhitespaceTokenIndex = $phpcsFile->findPrevious(
                            T_WHITESPACE,
                            $nextAliasInstanceIndex - 1,
                            null,
                            true);

                        $validFollowingTokenTypes = [
                            T_CURLY_OPEN,
                            T_VARIABLE,
                            T_DOUBLE_COLON
                        ];
                        $validPrecedingTokenTypes = [
                            T_COLON,
                            T_COMMA,
                            T_IMPLEMENTS,
                            T_EXTENDS,
                            T_NEW
                        ];
                        if ($nextNonWhitespaceTokenIndex
                            && in_array($tokens[$nextNonWhitespaceTokenIndex]['code'], $validFollowingTokenTypes)
                        ) {
                            /**
                             * alias is used in a "ClassName::" type statement
                             */
                            $namespaceIsUsed = true;
                        } elseif ($prevNonWhitespaceTokenIndex
                            && in_array($tokens[$prevNonWhitespaceTokenIndex]['code'], $validPrecedingTokenTypes)
                        ) {
                            /**
                             * alias is used in a "new ClassName()" type statement
                             */
                            $namespaceIsUsed = true;
                        } elseif ($tokens[$nextNonWhitespaceTokenIndex]['code'] === T_NS_SEPARATOR) {
                            /**
                             * alias is to a namespace under which a class is being referenced
                             */
                             $namespaceIsUsed = true;
                        }
                    }

                    $nextAliasInstanceIndex = $phpcsFile->findNext(
                        T_STRING,
                        $nextAliasInstanceIndex + 1,
                        null,
                        false,
                        $alias);
                }

                if (!$namespaceIsUsed) {
                    $phpcsFile->addError("Unused 'use' declaration found", $stackPtr, 'UnusedUse');
                }
                
                $endOfDeclarationToken = $tokens[$endOfDeclarationIndex];
                $endOfUseDeclarationReached = $endOfDeclarationToken['code'] == T_SEMICOLON;
                
                if (!$endOfUseDeclarationReached) {
                    /**
                     * keep on going until we hit the last alias in this use declaration
                     */
                    $endOfDeclarationIndex = $phpcsFile->findNext(
                        [T_COMMA, T_SEMICOLON],
                        $endOfDeclarationIndex + 1);
                }
            }
        }
    }
    
    /**
     * 
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $stackPtr
     * @return bool
     */
    private function isUseDeclaration(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        if ($tokens[$next]['code'] === T_OPEN_PARENTHESIS) {
           return false;
        } 
        
        if ($phpcsFile->hasCondition($stackPtr, [T_CLASS, T_TRAIT]) === true) {
            return false;
        }
        
        return true;
    }
    
}
