<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ScopeIndentTest extends TestCase
{
    
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
