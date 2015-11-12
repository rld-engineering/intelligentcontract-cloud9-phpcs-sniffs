<?php

use SomeClass as SomeClassAlias,
    AnotherClass as AnotherClassAlias;

class test
{

    private function foo ()
    {
        SomeClassAlias::foo();
        AnotherClassAlias::bar();
    }
    
}
