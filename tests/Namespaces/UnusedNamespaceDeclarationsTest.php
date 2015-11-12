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
            'used namespace' => ['used-namespace', []],
            'closure use statement' => ['closure-use', []],
            'trait use statement' => ['trait', []],
            'new statement' => ['new-statement', []],
            'multiple used namespaces' => ['multi-namespaces', []],
            'unused namespace' => [
                'unused-namespace',
                [
                    [3, 1, "Unused 'use' declaration found"]
                ]
            ]
        ];
    }
    
}
