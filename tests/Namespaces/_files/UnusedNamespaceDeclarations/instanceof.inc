<?php

declare(strict_types = 1);

use IntContPortal\FileUpload\Value\HasUserUploadedId;

class Test
{
    
    private function isJobFileInfected(\IntCont_BackgroundJob $job): bool
    {
        if (!($file instanceof HasUserUploadedId)) {
            return false;
        }
