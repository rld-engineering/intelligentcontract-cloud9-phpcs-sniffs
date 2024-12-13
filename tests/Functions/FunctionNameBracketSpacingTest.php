<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\Functions;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class FunctionNameBracketSpacingTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'correct spacing' => ['correct-spacing', []],
            'incorrect spacing' => [
                'incorrect-spacing',
                [
                    [6, 13, 'Space found between function name and opening parenthesis']
                ]
            ]
        ];
    }
    
}
