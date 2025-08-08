<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ClosingBracketNewLineTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'closing bracket plus method call' => [
                'closing-bracket-plus-method-call',
                [],
            ],
            'function call no args' => [
                'function-call-no-args',
                [],
            ],
            'function call one arg, one line' => [
                'function-call-one-arg-one-line',
                [],
            ],
            'function call one arg, two lines, correct' => [
                'function-call-one-arg-two-lines-correct',
                [],
            ],
            'multiple brackets on one line, correct' => [
                'multiple-brackets-correct',
                [],
            ],
            'function call one arg, two lines, incorrect' => [
                'function-call-one-arg-two-lines-incorrect',
                [
                    [
                        9,
                        20,
                        'Closing bracket must be on a new line',
                    ]
                ],
            ],
            'nested array' => [
                'nested-array',
                [],
            ],
            'multi line if' => [
                'multi-line-if',
                [],
            ],
        ];
    }
    
}
