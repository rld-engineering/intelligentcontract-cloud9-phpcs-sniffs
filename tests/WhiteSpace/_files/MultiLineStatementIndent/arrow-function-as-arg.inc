<?php

class Test
{

    public function test()
    {
        $object->method()
            ->arrowFunctionArg(fn (string $arg) => '1')
            ->anotherMethod($hello);
    }
    
}
