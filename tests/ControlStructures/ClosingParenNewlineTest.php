<?php

require_once __DIR__ . '/../TestCase.php';

class ClosingParenNewlineTest extends TestCase
{
    
    public function setUp(): void
    {
        $this->sniffName = 'Cloud9Software.ControlStructures.ClosingParenNewline';
    }
    
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