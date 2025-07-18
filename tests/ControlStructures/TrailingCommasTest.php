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
                []
            ],
            'multi line, incorrect' => [
                'multi-line-incorrect',
                [
                    [
                        10,
                        9,
                        'Last element of a multi-line comma separated list must have a trailing comma',
                    ]
                ]
            ],
        ];
    }
    
}
