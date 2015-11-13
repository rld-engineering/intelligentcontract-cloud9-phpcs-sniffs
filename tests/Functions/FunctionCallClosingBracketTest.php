<?php

require_once __DIR__ . '/../TestCase.php';

class Functions_FunctionCallClosingBracketTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.Functions.FunctionCallClosingBracket';
    }
    
    public function sniffProvider()
    {
        return [
            'single line call' => ['single-line', []],
            'multi line call' => ['multi-line', []],
            'object property reference' => ['object-property-reference', []],
            'multi line, closing bracket on newline' => [
                'multi-line-incorrect',
                [
                    [8, 14, 'Closing parenthesis of a function call must not be on a new line']
                ]
            ],
        ];
    }
    
}
