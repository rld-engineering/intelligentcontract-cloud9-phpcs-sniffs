<?php

require_once __DIR__ . '/../TestCase.php';

class ControlStructures_SwitchParenSpaceTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'Cloud9Software.ControlStructures.SwitchParenSpace';
    }
    
    public function sniffProvider()
    {
        return [
            'correct spacing' => [
                'correct-spacing',
                []
            ],
            'missing space' => [
                'missing-space',
                [
                    [
                        8,
                        9,
                        'Expected "switch (...) {\n"; found "switch(...) {\n"'
                    ]
                ]
            ]
        ];
    }
    
}