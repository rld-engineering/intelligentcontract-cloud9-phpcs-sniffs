<?php

class test
{
    
    public function foo()
    {
        $this->blah(
            function () {
                if (true) {
                    if (true) {
                        $this->foo();
                    }
                }
                $foo = 'asd';
            },
            2,
            '3');
    }
    
}