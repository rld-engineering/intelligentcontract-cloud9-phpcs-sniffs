<?php

require_once __DIR__ . '/../TestCase.php';

class Namespaces_UnusedNamespaceDeclarationsTest extends TestCase
{
    
    public function setUp()
    {
        $this->sniffName = 'HappyCustomer.Namespaces.UnusedNamespaceDeclarations';
    }
    
    public function sniffProvider()
    {
        return [
            'used namespace' => [__DIR__ . '/_files/UnusedNamespaceDeclarations/used-namespace.php', []],
            'unused namespace' => [
                __DIR__ . '/_files/UnusedNamespaceDeclarations/unused-namespace.php',
                [
                    [3, 1, "Unused 'use' declaration found"]
                ]
            ]
        ];
    }
    
}
