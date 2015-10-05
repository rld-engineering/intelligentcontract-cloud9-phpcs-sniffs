<?php

require_once __DIR__ . '/../TestCase.php';

class Functions_FunctionNameBracketSpacingTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.Functions.FunctionNameBracketSpacing';
    }
    
    public function sniffProvider()
    {
        return [
            'correct spacing' => [__DIR__ . '/_files/FunctionNameBracketSpacing/correct-spacing.php', []],
            'incorrect spacing' => [
                __DIR__ . '/_files/FunctionNameBracketSpacing/incorrect-spacing.php',
                [
                    [6, 13, 'Space found between function name and opening parenthesis']
                ]
            ]
        ];
    }
    
}
