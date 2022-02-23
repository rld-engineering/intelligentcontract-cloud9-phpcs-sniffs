<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class IfStatementSpacingTest extends TestCase
{
    
    public function sniffProvider()
    {
        return [
            'single arg, correct spacing' => ['correct-spacing', []],
            'first arg on new line' => ['first-arg-on-newline', []],
            'whitespace before args' => [
                'whitespace-before-args',
                [
                    [8, 9, "Whitespace found before 'if' statement conditions"]
                ]
            ],
        ];
    }
    
}