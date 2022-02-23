<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\WhiteSpace;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class SuperfluousNewlinesTest extends TestCase
{
    
    public function sniffProvider()
    {
        return [
            'no superfluous newlines' => [
                'no-newlines',
                []
            ],
            'single superfluous newline' => [
                'single-superf-newline',
                [
                    [9, 5, 'Superfluous newlines found before/after scope start/end']
                ]
            ]
        ];
    }
    
}
