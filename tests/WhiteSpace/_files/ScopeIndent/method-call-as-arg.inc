<?php

class test
{
    
    public function foo()
    {
        $this->bar($this->foo()->bar());
    }
    
}