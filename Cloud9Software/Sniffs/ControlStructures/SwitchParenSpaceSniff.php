<?php

declare(strict_types=1);

namespace Cloud9Software\Sniffs\ControlStructures;

final class SwitchParenSpaceSniff
    extends \PHP_CodeSniffer\Sniffs\AbstractPatternSniff
{

    public $ignoreComments = true;

    protected function getPatterns()
    {
        return [
            'switch (...) {EOL'
        ];
    }

}