<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\Files;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class StrictTypesDeclarationTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'declaration present' => [
                'correct',
                [],
            ],
            'declaration missing' => [
                'incorrect',
                [
                    [
                        1,
                        1,
                        'Missing strict_types declaration. Add: declare(strict_types=1);',
                    ]
                ],
            ],
        ];
    }

}
