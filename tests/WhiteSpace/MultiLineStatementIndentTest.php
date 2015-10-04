<?php

require_once __DIR__ . '/../TestCase.php';
require_once __DIR__ . '/../../HappyCustomer/Sniffs/WhiteSpace/DisallowContiguousNewlinesSniff.php';

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
            'indent too big' => [
                __DIR__ . '/_files/MultiLineStatementIndent/indent-too-big.php',
                [
                    [11, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ],
            'indent too big, integer args' => [
                __DIR__ . '/_files/MultiLineStatementIndent/indent-wrong-integers.php',
                [
                    [11, 17, 'Indent incorrect; expected 12, found 16']
                ]
            ],
        ];
    }
    
}