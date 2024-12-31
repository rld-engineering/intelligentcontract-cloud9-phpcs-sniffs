<?php

class Test
{
    
    public function test()
    {
        switch ($itemType) {
            case 'customer':
                $items = $this->customerGateway
                    ->getAll();
                break;
        }
    }
    
}
