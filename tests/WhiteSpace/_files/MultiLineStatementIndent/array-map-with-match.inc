<?php

readonly class ResponseParams implements ArrayFromRequest
{
    
    private function dataChanges(Request $request): array
    {
        return array_map(
            fn (array $changeDetails): DataChange => match ($variable) {
                '' => ''
            },
            $array
        );
    }
    
}
