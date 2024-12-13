<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\Functions;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class FunctionCallClosingBracketTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'single line call' => ['single-line', []],
            'multi line call' => ['multi-line', []],
            'object property reference' => ['object-property-reference', []],
            'multi line, closing bracket on newline' => [
                'multi-line-incorrect',
                [
                    [8, 14, 'Closing parenthesis of a function call must not be on a new line']
                ]
            ],
        ];
    }
    
}
