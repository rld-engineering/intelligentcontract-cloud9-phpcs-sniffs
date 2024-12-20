<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class ScopeIndentTest extends TestCase
{
    
    public static function sniffProvider()
    {
        return [
            'if, incorrect indent' => [
                'if-incorrect',
                [
                    [
                        9,
                        12,
                        'Indent incorrect; expected 12, found 11'
                    ]
                ]
            ],
            'single return in method, incorrect' => [
                'single-line-return-incorrect',
                [
                    [
                        8,
                        8,
                        'Indent incorrect; expected 8, found 7'
                    ]
                ]
            ],
            'define' => ['define', []],
            'closure as arg' => ['closure-arg', []],
            'method call as arg' => ['method-call-as-arg', []],
            'multi line for' => ['multi-line-for', []],
            'object operator' => ['object-operator', []],
            'switch statement' => ['switch-statement', []],
            'match statement' => [
                'match-statement',
                []
            ],
            'match following arrow function' => [
                'match-following-arrow-function',
                []
            ],
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
