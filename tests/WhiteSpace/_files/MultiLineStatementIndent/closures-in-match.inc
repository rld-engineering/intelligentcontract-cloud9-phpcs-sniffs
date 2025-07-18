<?php

class test
{

    private function populateDataSetupClosure(string $key): \Closure
    {
        return match ($key) {
            'case 1' => static function (): void {
            },
            'case 2' => static function (): void {
            },
            'case 3' => static function (): void {
            },
            default => static function (): void {
            }
        };
    }

}
