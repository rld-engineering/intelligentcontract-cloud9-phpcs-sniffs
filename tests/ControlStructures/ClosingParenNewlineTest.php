<?php

require_once __DIR__ . '/../TestCase.php';

class ControlStructures_ClosingParenNewlineTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.ControlStructures.ClosingParenNewline';
    }
    
    public function sniffProvider()
    {
        return [
            'incomplete for structure' => [__DIR__ . '/_files/ClosingParenNewline/incomplete-for.php', []],
            'single line structure' => [__DIR__ . '/_files/ClosingParenNewline/single-line-structure.php', []],
            'correct multi-line structure' => [__DIR__ . '/_files/ClosingParenNewline/multi-line-structure.php', []],
            'inccorrect multi-line structure' => [
                __DIR__ . '/_files/ClosingParenNewline/multi-line-structure-incorrect.php',
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