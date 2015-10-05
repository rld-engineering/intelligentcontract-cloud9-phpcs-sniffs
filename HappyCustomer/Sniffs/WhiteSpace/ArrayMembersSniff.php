<?php

class HappyCustomer_Sniffs_Whitespace_ArrayMembersSniff
    implements PHP_CodeSniffer_Sniff
{
    
    public function register()
    {
        return array(T_ARRAY);
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $firstMemberTokenIndex
     * @return void
     */
    private function checkArrayMembersAreSeparatedByWhiteSpace(PHP_CodeSniffer_File $phpcsFile, $firstMemberTokenIndex)
    {
        $tokens = $phpcsFile->getTokens();
        
        $nextArrayMemberIndex = $phpcsFile->findNext(
            array(T_WHITESPACE),
            $firstMemberTokenIndex + 1,
            null,
            true);

        $isThisTheFirstArrayMember = true;
        while ($nextArrayMemberIndex) {
            /**
             * check there's some whitespace after this token
             */
            $indexOfTokenPrecedingMember = $nextArrayMemberIndex - 1;
            $tokenPrecedingNextMember = $tokens[$indexOfTokenPrecedingMember];
            if (!$isThisTheFirstArrayMember && $tokenPrecedingNextMember['code'] != T_WHITESPACE) {
                $phpcsFile->addError(
                    "Array members must be separated by a single space or a line-break",
                    $indexOfTokenPrecedingMember,
                    'ArrayIndent');
                return;
            }

            $isThisTheFirstArrayMember = false;
            $nextArrayMemberIndex = $this->getNextArrayMemberIndex($phpcsFile, $nextArrayMemberIndex + 1);
        }
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $arrayDeclarationCloseIndex
     * @return bool
     */
    private function checkClosingParenIsOnItsOwnLine(PHP_CodeSniffer_File $phpcsFile, $arrayDeclarationCloseIndex)
    {
        $firstTokenOnLastLineIndex = $phpcsFile->findFirstOnLine(
            array(T_WHITESPACE),
            $arrayDeclarationCloseIndex,
            true);
        $isClosingParenOnItsOwnLine = $firstTokenOnLastLineIndex == $arrayDeclarationCloseIndex;
        if (!$isClosingParenOnItsOwnLine) {
            $phpcsFile->addError(
                'Closing parenthesis of multi-line array declaration must be on its own line',
                $arrayDeclarationCloseIndex,
                'ArrayIndent');
            return false;
        }
        
        return true;
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $firstMemberTokenIndex = $phpcsFile->findNext(array(T_WHITESPACE), $stackPtr + 1, null, true);
        
        if (!$firstMemberTokenIndex) {
            return;
        }
        
        $tokens = $phpcsFile->getTokens();
        $arrayDeclarationOpen = $tokens[$firstMemberTokenIndex];
        
        $arrayDeclarationCloseIndex = $this->getArrayDeclarationCloseIndex($phpcsFile, $firstMemberTokenIndex);
        
        if (!$arrayDeclarationCloseIndex) {
            return;
        }
        
        $arrayDeclarationClose = $tokens[$arrayDeclarationCloseIndex];
        
        $isArrayDeclarationOnOneLine = $arrayDeclarationClose['line'] == $arrayDeclarationOpen['line'];
        if ($isArrayDeclarationOnOneLine) {
            $this->checkArrayMembersAreSeparatedByWhiteSpace($phpcsFile, $firstMemberTokenIndex);
            return;
        }
        
        if (!$this->checkClosingParenIsOnItsOwnLine($phpcsFile, $arrayDeclarationCloseIndex)) {
            return;
        }
        
        $this->checkMultiLineArrayIndents($phpcsFile, $stackPtr);
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $arrayDeclarationLineStartIndex
     * @return void
     */
    private function checkMultiLineArrayIndents(
        PHP_CodeSniffer_File $phpcsFile,
        $arrayDeclarationLineStartIndex
    ) {
        $tokens = $phpcsFile->getTokens();
        
        $firstMemberTokenIndex = $phpcsFile->findNext(
            array(T_WHITESPACE),
            $arrayDeclarationLineStartIndex + 1,
            null,
            true);
        
        $nextArrayMemberIndex = $phpcsFile->findNext(
            array(T_WHITESPACE),
            $firstMemberTokenIndex + 1,
            null,
            true);
        
        $arrayDeclarationLineStart = $tokens[$arrayDeclarationLineStartIndex];
        
        while ($nextArrayMemberIndex) {
            $nextArrayMemberToken = $tokens[$nextArrayMemberIndex];
            
            $expectedIndent = $arrayDeclarationLineStart['column'] + 4;
            
            if ($nextArrayMemberToken['column'] != $expectedIndent) {
                $phpcsFile->addError(
                    "Indent incorrect; expected " . ($expectedIndent - 1)
                    . ', found ' . ($nextArrayMemberToken['column'] - 1)
                    . ' (members of multi-line array declaration must be one per line, with no trailing comma)',
                    $nextArrayMemberIndex,
                    'ArrayIndent');
            }
            
            $nextArrayMemberIndex = $this->getNextArrayMemberIndex($phpcsFile, $nextArrayMemberIndex + 1);
        }
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return int
     */
    private function getNextArrayMemberIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokensToScanFor = array(
            T_OPEN_PARENTHESIS,
            T_CLOSE_PARENTHESIS,
            T_COMMA
        );
        
        $nextCommaOrParenthesisIndex = $phpcsFile->findNext(
            $tokensToScanFor,
            $stackPtr);
        $tokens = $phpcsFile->getTokens();
        $parenCount = 0;
        
        while ($nextCommaOrParenthesisIndex) {
            $token = $tokens[$nextCommaOrParenthesisIndex];

            switch ($token['code']) {
                case T_COMMA:
                    $weAreNotInANestedStructure = !$parenCount;
                    if ($weAreNotInANestedStructure) {
                        return $phpcsFile->findNext(array(T_WHITESPACE), $nextCommaOrParenthesisIndex + 1, null, true);
                    }
                    break;
                case T_OPEN_PARENTHESIS:
                    $parenCount++;
                    break;
                case T_CLOSE_PARENTHESIS:
                    if (!$parenCount) {
                        return null;
                    }
                    
                    $parenCount--;
                    break;
                default:
                    throw new Exception('unhandled code: ' . $token['code']);
            }
            
            $nextCommaOrParenthesisIndex = $phpcsFile->findNext(
                $tokensToScanFor,
                $nextCommaOrParenthesisIndex + 1);
        }
        
        return null;
    }
    
    /**
     * 
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @return int
     */
    private function getArrayDeclarationCloseIndex(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        $openParenCount = 0;
        
        $tokenTypesToScanFor = array(T_CLOSE_PARENTHESIS, T_OPEN_PARENTHESIS);
        
        $nextParenTokenIndex = $phpcsFile->findNext(
            $tokenTypesToScanFor,
            $stackPtr + 1);
        
        while ($nextParenTokenIndex) {
            $nextParenToken = $tokens[$nextParenTokenIndex];
            
            $weAreNotInANestedStructure = !$openParenCount;
            if ($weAreNotInANestedStructure && $nextParenToken['code'] == T_CLOSE_PARENTHESIS) {
                return $nextParenTokenIndex;
            }
            
            $openParenCount += ($nextParenToken['code'] == T_OPEN_PARENTHESIS) ? 1 : -1;
            
            $nextParenTokenIndex = $phpcsFile->findNext(
                $tokenTypesToScanFor,
                $nextParenTokenIndex + 1);
        }
        
        return null;
    }
    
}