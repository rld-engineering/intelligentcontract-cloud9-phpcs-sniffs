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
            'single arg, correct spacing' => [__DIR__ . '/_files/IfStatementSpacing/correct-spacing.php', []],
            'first arg on new line' => [__DIR__ . '/_files/IfStatementSpacing/first-arg-on-newline.php', []],
            'whitespace before args' => [
                __DIR__ . '/_files/IfStatementSpacing/whitespace-before-args.php',
                [
                    [8, 9, "Whitespace found before 'if' statement conditions"]
                ]
            ],
        ];
    }
    
}