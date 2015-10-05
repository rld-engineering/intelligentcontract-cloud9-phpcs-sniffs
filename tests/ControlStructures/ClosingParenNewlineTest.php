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
            'single line structure' => [__DIR__ . '/_files/ClosingParenNewline/single-line-structure.php', []]
        ];
    }
    
}