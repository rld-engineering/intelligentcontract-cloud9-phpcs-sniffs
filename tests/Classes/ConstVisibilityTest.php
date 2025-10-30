<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\Classes;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ConstVisibilityTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'with visibility' => ['with-visibility', []],
            'without visibility' => [
                'without-visibility',
                [
                    [6, 5, 'Class constant must have a visibility modifier (public, protected, or private)'],
                    [7, 5, 'Class constant must have a visibility modifier (public, protected, or private)']
                ]
            ],
            'mixed visibility' => [
                'mixed-visibility',
                [
                    [7, 5, 'Class constant must have a visibility modifier (public, protected, or private)']
                ]
            ],
            'global const' => ['global-const', []],
            'interface const' => [
                'interface-const',
                [
                    [6, 5, 'Class constant must have a visibility modifier (public, protected, or private)']
                ]
            ]
        ];
    }

}

