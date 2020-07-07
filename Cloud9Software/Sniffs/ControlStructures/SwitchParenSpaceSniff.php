<?php

class Cloud9Software_Sniffs_ControlStructures_SwitchParenSpaceSniff
    extends PHP_CodeSniffer\Sniffs\AbstractPatternSniff
{
    
    public $ignoreComments = true;

    protected function getPatterns()
    {
        return [
            'switch (...) {EOL'
        ];
    }
    
}