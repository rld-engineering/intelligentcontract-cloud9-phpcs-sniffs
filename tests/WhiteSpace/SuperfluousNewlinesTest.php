<?php

require_once __DIR__ . '/../TestCase.php';

class SuperfluousNewlinesTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'Cloud9Software.WhiteSpace.SuperfluousNewlines';
    }
    
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
