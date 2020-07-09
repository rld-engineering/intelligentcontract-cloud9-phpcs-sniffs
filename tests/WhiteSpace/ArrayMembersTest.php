<?php

require_once __DIR__ . '/../TestCase.php';

class ArrayMembersTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'Cloud9Software.WhiteSpace.ArrayMembers';
    }
    
    public function sniffProvider()
    {
        return [
            'array assignment' => ['array-assignment', []],
            'empty method' => ['empty-method', []],
            'single line array declaration' => ['single-line-array', []],
            'empty array declaration' => ['empty-array-declaration', []],
            'multi line array declaration' => ['multi-line-array', []],
            'nested array' => [ 'nested-array', []],
            'single line array hanging comma' => [
                'single-line-array-hanging-comma',
                [
                    [8, 22, 'Array members must be separated by a single space or a line-break']
                ]
            ],
            'multi line array hanging comma' => [
                'multi-line-array-hanging-comma',
                [
                    [
                        12,
                        9,
                        'Indent incorrect; expected 12, found 8 (members of multi-line '
                        . 'array declaration must be one per line, with no trailing comma)']
                ]
            ],
            'multi line array incorrect indent' => [
                'multi-line-incorrect-indent',
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
                'multi-line-closing-paren-wrong',
                [
                    [11, 14, 'Closing parenthesis of multi-line array declaration must be on its own line']
                ]
            ]
        ];
    }
    
}