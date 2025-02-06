<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs\Namespaces;

use Cloud9Software\Sniffs\TestCase;

require_once __DIR__ . '/../TestCase.php';

class UnusedNamespaceDeclarationsTest extends TestCase
{

    public static function sniffProvider()
    {
        return [
            'used in phpdoc' => [
                'phpdoc',
                []
            ],
            'attribute' => [
                'attribute',
                []
            ],
            'instanceof' => ['instanceof', []],
            'return type' => ['return-type', []],
            'nullable return type' => ['nullable-return-type', []],
            'implements' => ['implements', []],
            'extends' => ['extends', []],
            'used as nullable' => [
                'used-as-nullable',
                []
            ],
            'used namespace' => ['used-namespace', []],
            'closure use statement' => ['closure-use', []],
            'trait use statement' => ['trait', []],
            'aliased trait from use statement' => ['aliased-trait', []],
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
