<?php

require_once __DIR__ . '/../TestCase.php';

class WhiteSpace_SuperfluousNewlinesTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.WhiteSpace.SuperfluousNewlines';
    }
    
    public function sniffProvider()
    {
        return [
            'no superfluous newlines' => [
                __DIR__ . '/_files/SuperfluousNewlines/no-newlines.php',
                []
            ],
            'single superfluous newline' => [
                __DIR__ . '/_files/SuperfluousNewlines/single-superf-newline.php',
                [
                    [9, 5, 'Superfluous newlines found before/after scope start/end']
                ]
            ]
        ];
    }
    
}
