<?php

class test
{
    
    public function foo()
    {
        $this->blah(
            $this->foo(),
            2,
            '3');
    }
    
}