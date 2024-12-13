<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\ControlStructures;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class SwitchParenSpaceTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'correct spacing' => [
                'correct-spacing',
                []
            ],
            'missing space' => [
                'missing-space',
                [
                    [
                        8,
                        9,
                        'Expected "switch (...) {\n"; found "switch(...) {\n"'
                    ]
                ]
            ]
        ];
    }
    
}
