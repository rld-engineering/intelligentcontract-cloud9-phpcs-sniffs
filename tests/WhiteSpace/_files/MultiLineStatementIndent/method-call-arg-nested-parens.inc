<?php

class test
{
    
    public function foo()
    {
        $this->blah(
            $this->foo($this->bar()),
            2,
            '3');
    }
    
}