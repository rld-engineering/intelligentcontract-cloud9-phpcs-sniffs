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
            'empty method' => [__DIR__ . '/_files/DisallowContiguousNewlines/empty-method.php', []]
        ];
    }
    
}