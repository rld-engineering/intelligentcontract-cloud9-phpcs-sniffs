<?php

require_once __DIR__ . '/../TestCase.php';

class Strings_ConcatenationSpacingTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.Strings.ConcatenationSpacing';
    }
    
    public function sniffProvider()
    {
        return [
            'correct spacing' => [__DIR__ . '/_files/ConcatenationSpacing/correct-spacing.php', []],
            'too many spaces' => [
                __DIR__ . '/_files/ConcatenationSpacing/too-many-spaces.php',
                [
                    [8, 23, "More than one space found between concat operator and adjacent expression"]
                ]
            ],
            'no spaces' => [
                __DIR__ . '/_files/ConcatenationSpacing/no-spaces.php',
                [
                    [8, 21, "Non-whitespace character found adjacent to string concat operator"]
                ]
            ]
        ];
    }
    
}
