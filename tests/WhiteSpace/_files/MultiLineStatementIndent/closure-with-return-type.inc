<?php

class Test
{
    
    public function test()
    {
        $parts = array_map(
            fn (string $fileName): string => file_get_contents($fileName),
            $assets
        );
        $parts = array_map(
            function (string $fileName): string {
                return file_get_contents($fileName);
            },
            $assets
        );
    }
    
}
