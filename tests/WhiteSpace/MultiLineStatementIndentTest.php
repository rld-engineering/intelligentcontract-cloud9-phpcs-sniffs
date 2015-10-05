<?php

require_once __DIR__ . '/../TestCase.php';

class WhiteSpace_MultiLineStatementIndentTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.WhiteSpace.MultiLineStatementIndent';
    }
    
    public function sniffProvider()
    {
        return [
            'empty method' => [__DIR__ . '/_files/MultiLineStatementIndent/empty-method.php', []],
            'two statements' => [__DIR__ . '/_files/MultiLineStatementIndent/two-statements.php', []],
            'use operator' => [__DIR__ . '/_files/MultiLineStatementIndent/use-operator.php', []],
            'method call arg' => [__DIR__ . '/_files/MultiLineStatementIndent/method-call-arg.php', []],
            'chained method call' => [__DIR__ . '/_files/MultiLineStatementIndent/chained-method-call.php', []],
            'chained method arg' => [__DIR__ . '/_files/MultiLineStatementIndent/chained-method-call-arg.php', []],
            'ternary operator arg' => [__DIR__ . '/_files/MultiLineStatementIndent/ternary-operator.php', []],
            'multi line chained method call arg' => [
                __DIR__ . '/_files/MultiLineStatementIndent/multi-line-chained-method-call-arg.php',
                []
            ],
            'method call arg with nested parens' => [
                __DIR__ . '/_files/MultiLineStatementIndent/method-call-arg-nested-parens.php',
                []
            ],
            'indent too big' => [
                __DIR__ . '/_files/MultiLineStatementIndent/indent-too-big.php',
                [
                    [11, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ],
            'indent too small' => [
                __DIR__ . '/_files/MultiLineStatementIndent/indent-too-small.php',
                [
                    [11, 9, 'Indent incorrect; expected 12, found 8']
                ]
            ],
            'indent too big, integer args' => [
                __DIR__ . '/_files/MultiLineStatementIndent/indent-wrong-integers.php',
                [
                    [11, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ],
            'correct indent with closure arg' => [__DIR__ . '/_files/MultiLineStatementIndent/closure-arg.php', []]
        ];
    }
    
}