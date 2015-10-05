<?php

class test
{
    
    public function foo()
    {
        $this->blah(
            function () {
                $this->foo();
            },
            2,
            '3');
    }
    
}