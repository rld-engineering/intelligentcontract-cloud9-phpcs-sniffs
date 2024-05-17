<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ArrowFunctionBracketSpaceTest extends TestCase
{

    public function sniffProvider()
    {
        return [
            'correct spacing' => [
                'space-before-bracket',
                []
            ],
            'space missing' => [
                'no-space-before-bracket',
                [
                    [
                        8,
                        21,
                        '"fn" must be followed by a space'
                    ]
                ]
            ]
        ];
    }
    
}
