<?php

class Cloud9Software_Sniffs_ControlStructures_SwitchParenSpaceSniff
    extends PHP_CodeSniffer_Standards_AbstractPatternSniff
{
    
    public $ignoreComments = true;

    protected function getPatterns()
    {
        return [
            'switch (...) {EOL'
        ];
    }
    
}