<?php

class HappyCustomer_Sniffs_Strings_ConcatenationSpacingSniff
    implements PHP_CodeSniffer_Sniff
{
    
    const ADJACENT_BEFORE = 'before';
    const ADJACENT_AFTER = 'after';
    
    public function register()
    {
        return array(T_STRING_CONCAT);
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        /**
         * make sure the tokens immediately following and preceding this one are whitespace
         */
        $previousToken = $tokens[$stackPtr - 1];
        $nextToken = $tokens[$stackPtr + 1];
        
        if ($previousToken['code'] != T_WHITESPACE || $nextToken['code'] != T_WHITESPACE) {
            $phpcsFile->addError(
                'Non-whitespace character found adjacent to string concat operator',
                $stackPtr,
                'StringConcatNoSpace');
            return;
        }
        
        if ($this->moreThanOneSpaceBetweenConcatAndAdjacentExpression($tokens, $stackPtr, self::ADJACENT_BEFORE)) {
            $phpcsFile->addError(
                'More than one space found between concat operator and adjacent expression',
                $stackPtr,
                'StringConcatTooManySpaces');
        }
        
        if ($this->moreThanOneSpaceBetweenConcatAndAdjacentExpression($tokens, $stackPtr, self::ADJACENT_AFTER)) {
            $phpcsFile->addError(
                'More than one space found between concat operator and adjacent expression',
                $stackPtr,
                'StringConcatTooManySpaces');
        }
    }
    
    /**
     * 
     * @param array $tokens
     * @param int $stackPtr
     * @param string $beforeOrAfter
     * @return bool
     */
    private function moreThanOneSpaceBetweenConcatAndAdjacentExpression(array $tokens, $stackPtr, $beforeOrAfter)
    {
        $beforeOrAfterMultiplier = $beforeOrAfter == self::ADJACENT_BEFORE ? -1 : 1;
        
        $concatToken = $tokens[$stackPtr];
        $nextButOneToken = $tokens[$stackPtr + ($beforeOrAfterMultiplier * 2)];
        $adjacentWhitespaceToken = $tokens[$stackPtr - ($beforeOrAfterMultiplier * 1)];
        
        $nextButOneTokenIsOnSameLine = $nextButOneToken['line'] = $concatToken['line'];
        $whitespaceIsMoreThanOneCharacter = $adjacentWhitespaceToken['length'] > 1;
        
        return $nextButOneTokenIsOnSameLine && $whitespaceIsMoreThanOneCharacter;
    }
    
}