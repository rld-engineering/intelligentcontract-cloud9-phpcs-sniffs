<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class MultiLineStatementIndentTest extends TestCase
{
    
    public function sniffProvider()
    {
        return [
            'empty method' => ['empty-method', []],
            'two statements' => ['two-statements', []],
            'use operator' => ['use-operator', []],
            'method call arg' => ['method-call-arg', []],
            'chained method call' => ['chained-method-call', []],
            'chained method arg' => ['chained-method-call-arg', []],
            'ternary operator arg' => ['ternary-operator', []],
            'multi line chained method call arg' => [
                'multi-line-chained-method-call-arg',
                []
            ],
            'method call arg with nested parens' => [
                'method-call-arg-nested-parens',
                []
            ],
            'indent too big' => [
                'indent-too-big',
                [
                    [11, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ],
            'indent too small' => [
                'indent-too-small',
                [
                    [11, 9, 'Indent incorrect; expected 12, found 8']
                ]
            ],
            'indent too big, integer args' => [
                'indent-wrong-integers',
                [
                    [11, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ],
            'correct indent with closure arg' => ['closure-arg', []],
            'long array declaration' => ['long-array', []],
            'short array declaration' => ['short-array', []],
            'nested short array declaration' => [
                'nested-short-array',
                []
            ]
        ];
    }
    
}