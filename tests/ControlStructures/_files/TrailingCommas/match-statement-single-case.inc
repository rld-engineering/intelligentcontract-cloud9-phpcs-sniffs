<?php

class test
{

    private function foo()
    {
        return match ($this) {
            self::SUBMITTED => 'Submitted'
        };
    }

}
