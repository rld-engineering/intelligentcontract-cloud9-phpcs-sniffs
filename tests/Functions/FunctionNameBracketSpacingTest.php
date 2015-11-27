<?php

require_once __DIR__ . '/../TestCase.php';

class Functions_FunctionNameBracketSpacingTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'Cloud9Software.Functions.FunctionNameBracketSpacing';
    }
    
    public function sniffProvider()
    {
        return [
            'correct spacing' => ['correct-spacing', []],
            'incorrect spacing' => [
                'incorrect-spacing',
                [
                    [6, 13, 'Space found between function name and opening parenthesis']
                ]
            ]
        ];
    }
    
}
