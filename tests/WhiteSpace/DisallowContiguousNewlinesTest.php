<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class DisallowContiguousNewlinesTest extends TestCase
{

    public function sniffProvider()
    {
        return [
            'empty lines at end' => [
                'empty-lines-end', []
            ],
            'empty method' => ['empty-method', []],
            'two lines separated by space' => [
                'two-lines-space-between',
                []
            ],
            'empty method with contiguous newlines' => [
                'empty-method-contiguous-newlines',
                [
                    [9, 1, 'Contiguous blank lines found']
                ]
            ],
            'two lines separated by contiguous newlines' => [
                'two-lines-contiguous-newlines',
                [
                    [10, 1, 'Contiguous blank lines found']
                ]
            ],
            'empty lines at start' => [
                'empty-lines-start',
                [
                    [3, 1, 'Contiguous blank lines found']
                ]
            ]
        ];
    }
    
}