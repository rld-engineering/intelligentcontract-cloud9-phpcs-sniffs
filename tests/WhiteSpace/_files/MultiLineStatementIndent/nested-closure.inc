<?php

class test
{
    
    public function foo()
    {
        array_map(
            static function (): array {
                array_map(
                    static function () {
                        return '';
                    },
                    $someArray,
                );
            },
            $mockResults,
        );
    }
    
}
