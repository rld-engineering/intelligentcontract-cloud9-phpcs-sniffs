<?php

require_once __DIR__ . '/../TestCase.php';

class WhiteSpace_MultiLineIfConditionTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'Cloud9Software.WhiteSpace.MultiLineIfCondition';
    }
    
    public function sniffProvider()
    {
        return [
            'single line condition' => ['single-line', []],
            'multi line condition, correct' => ['multi-line-correct', []],
            'multi line condition, incorrect' => [
                'multi-line-incorrect',
                [
                    [8, 9, 'Closing paren should be on the same column as "if"']
                ]
            ]
        ];
    }
    
}