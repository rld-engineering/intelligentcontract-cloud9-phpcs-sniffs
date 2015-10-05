<?php

require_once __DIR__ . '/../TestCase.php';

class WhiteSpace_ArrayMembersTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.WhiteSpace.ArrayMembers';
    }
    
    public function sniffProvider()
    {
        return [
            'empty method' => [__DIR__ . '/_files/ArrayMembers/empty-method.php', []],
            'incomplete array declaration' => [__DIR__ . '/_files/ArrayMembers/incomplete-array-declaration.php', []],
            'single line array declaration' => [__DIR__ . '/_files/ArrayMembers/single-line-array.php', []],
            'empty array declaration' => [__DIR__ . '/_files/ArrayMembers/empty-array-declaration.php', []],
            'multi line array declaration' => [__DIR__ . '/_files/ArrayMembers/multi-line-array.php', []],
            'nested array' => [__DIR__ . '/_files/ArrayMembers/nested-array.php', []],
            'single line array hanging comma' => [
                __DIR__ . '/_files/ArrayMembers/single-line-array-hanging-comma.php',
                [
                    [8, 22, 'Array members must be separated by a single space or a line-break']
                ]
            ],
            'multi line array hanging comma' => [
                __DIR__ . '/_files/ArrayMembers/multi-line-array-hanging-comma.php',
                [
                    [
                        12,
                        9,
                        'Indent incorrect; expected 12, found 8 (members of multi-line '
                        . 'array declaration must be one per line, with no trailing comma)']
                ]
            ],
            'multi line array incorrect indent' => [
                __DIR__ . '/_files/ArrayMembers/multi-line-incorrect-indent.php',
                [
                    [
                        10,
                        9,
                        'Indent incorrect; expected 12, found 8 (members of multi-line '
                        . 'array declaration must be one per line, with no trailing comma)'
                    ]
                ]
            ],
            'multi line array, closing paren on wrong line' => [
                __DIR__ . '/_files/ArrayMembers/multi-line-closing-paren-wrong.php',
                [
                    [11, 14, 'Closing parenthesis of multi-line array declaration must be on its own line']
                ]
            ]
        ];
    }
    
}