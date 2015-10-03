<?php

require_once __DIR__ . '/../TestCase.php';
require_once __DIR__ . '/../../HappyCustomer/Sniffs/WhiteSpace/DisallowContiguousNewlinesSniff.php';

class WhiteSpace_DisallowContiguousNewlinesTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.WhiteSpace.DisallowContiguousNewlines';
    }
    
    public function sniffProvider()
    {
        return [
            'empty method' => [__DIR__ . '/_files/DisallowContiguousNewlines/empty-method.php', []],
            'two lines separated by space' => [
                __DIR__ . '/_files/DisallowContiguousNewlines/two-lines-space-between.php',
                []
            ],
            'empty method with contiguous newlines' => [
                __DIR__ . '/_files/DisallowContiguousNewlines/empty-method-contiguous-newlines.php',
                [
                    [9, 1, 'Contiguous blank lines found']
                ]
            ],
            'two lines separated by contiguous newlines' => [
                __DIR__ . '/_files/DisallowContiguousNewlines/two-lines-contiguous-newlines.php',
                [
                    [10, 1, 'Contiguous blank lines found']
                ]
            ],
            'empty lines at start' => [
                __DIR__ . '/_files/DisallowContiguousNewlines/empty-lines-start.php',
                [
                    [3, 1, 'Contiguous blank lines found']
                ]
            ]
        ];
    }
    
}