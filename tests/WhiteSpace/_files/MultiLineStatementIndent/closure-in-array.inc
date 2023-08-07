<?php

declare(strict_types = 1);

class Test
{
    
    public function test()
    {
        return [
            'riskDefs' => array_map(
                function ($riskDef) {
                    return '1';
                },
                $riskDefs->toArray()
            ),
            'riskSeverities' => $riskSeverities
        ];
    }
    
}
