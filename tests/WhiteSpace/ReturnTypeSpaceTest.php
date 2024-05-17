<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ReturnTypeSpaceTest extends TestCase
{

    public function sniffProvider()
    {
        return [
            'newline before type' => [
                'newline-before-type',
                []
            ],
            'correct spacing' => [
                'space-before-type',
                []
            ],
            'space missing' => [
                'no-space-before-type',
                [
                    [
                        6,
                        13,
                        '":" must be followed by a space'
                    ]
                ]
            ]
        ];
    }
    
}
