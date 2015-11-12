<?php

require_once __DIR__ . '/../TestCase.php';

class WhiteSpace_IfStatementSpacingTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.WhiteSpace.IfStatementSpacing';
    }
    
    public function sniffProvider()
    {
        return [
            'single arg, correct spacing' => ['correct-spacing', []],
            'first arg on new line' => ['first-arg-on-newline', []],
            'whitespace before args' => [
                'whitespace-before-args',
                [
                    [8, 9, "Whitespace found before 'if' statement conditions"]
                ]
            ],
        ];
    }
    
}