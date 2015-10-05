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
            'switch statement' => [__DIR__ . '/_files/ScopeIndent/switch-statement.php', []],
            'structure followed by statemment' => [__DIR__ . '/_files/ScopeIndent/structure-then-statement.php', []],
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