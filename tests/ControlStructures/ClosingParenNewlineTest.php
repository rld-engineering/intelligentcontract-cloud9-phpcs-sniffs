<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\ControlStructures;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ClosingParenNewlineTest extends TestCase
{

    public function sniffProvider()
    {
        return [
            'incomplete for structure' => ['incomplete-for', []],
            'single line structure' => ['single-line-structure', []],
            'correct multi-line structure' => ['multi-line-structure', []],
            'inccorrect multi-line structure' => [
                'multi-line-structure-incorrect',
                [
                    [
                        8,
                        9,
                        'Closing parenthesis and "{" of a multi-line control structure expression should be '
                        . 'on their own line']
                ]
            ]
        ];
    }
    
}