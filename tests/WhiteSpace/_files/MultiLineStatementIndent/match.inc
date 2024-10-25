<?php

class Test {

    public function dependency()
    {
        return match ($className) {
            'At' => '456',
            'Ecs' => '123'
        };
    }

}
