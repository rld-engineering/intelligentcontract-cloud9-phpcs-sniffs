<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\Strings;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ConcatenationSpacingTest extends TestCase
{

    public function sniffProvider()
    {
        return [
            'correct spacing' => ['correct-spacing', []],
            'too many spaces' => [
                'too-many-spaces',
                [
                    [8, 23, "More than one space found between concat operator and adjacent expression"]
                ]
            ],
            'too many spaces after' => [
                'too-many-spaces-after',
                [
                    [8, 22, "More than one space found between concat operator and adjacent expression"]
                ]
            ],
            'no spaces' => [
                'no-spaces',
                [
                    [8, 21, "Non-whitespace character found adjacent to string concat operator"]
                ]
            ]
        ];
    }
    
}
