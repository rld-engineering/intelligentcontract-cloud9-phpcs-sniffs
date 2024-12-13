<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class MultiLineIfConditionTest extends TestCase
{
    
    public static function sniffProvider()
    {
        return [
            'single line condition' => ['single-line', []],
            'multi line condition, correct' => ['multi-line-correct', []],
            'multi line condition, incorrect' => [
                'multi-line-incorrect',
                [
                    [8, 9, 'Closing paren should be on the same column as "if"']
                ]
            ]
        ];
    }
    
}
