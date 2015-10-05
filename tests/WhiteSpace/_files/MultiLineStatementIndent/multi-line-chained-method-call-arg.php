<?php

class test
{
    
    public function foo()
    {
        $this->blah(
            $this->foo()
                ->bar(),
            2,
            '3');
    }
    
}