<?php

require_once __DIR__ . '/../TestCase.php';

class WhiteSpace_ScopeIndentTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.WhiteSpace.ScopeIndent';
    }
    
    public function sniffProvider()
    {
        return [
            'empty method' => [__DIR__ . '/_files/ScopeIndent/empty-method.php', []],
            'scope incorrect indent' => [
                __DIR__ . '/_files/ScopeIndent/scope-incorrect-indent.php',
                [
                    [9, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ]
        ];
    }
    
}