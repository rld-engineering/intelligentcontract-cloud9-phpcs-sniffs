<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\ControlStructures;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

final class TrailingCommasTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'multi line, correct' => [
                'multi-line-correct',
                [],
            ],
            'method args, correct' => [
                'method-args-correct',
                [],
            ],
            'expression in parens' => [
                'if-expression-in-parens',
                [],
            ],
            'method args, incorrect' => [
                'method-args-incorrect',
                [
                    [
                        8,
                        16,
                        'Multi-line list must end with a trailing comma.',
                    ],
                ],
            ],
            'multi line, incorrect' => [
                'multi-line-incorrect',
                [
                    [
                        9,
                        13,
                        'Multi-line list must end with a trailing comma.',
                    ],
                ],
            ],
        ];
    }
    
}
