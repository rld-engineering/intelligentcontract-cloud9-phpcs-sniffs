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
            'define' => ['define', []],
            'closure as arg' => ['closure-arg', []],
            'method call as arg' => ['method-call-as-arg', []],
            'multi line for' => ['multi-line-for', []],
            'object operator' => ['object-operator', []],
            'switch statement' => ['switch-statement', []],
            'structure followed by statemment' => ['structure-then-statement', []],
            'empty method' => ['empty-method', []],
            'scope incorrect indent' => [
                'scope-incorrect-indent',
                [
                    [9, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ]
        ];
    }
    
}