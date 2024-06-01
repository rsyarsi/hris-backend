<?php

namespace App\Services\CopyFile;

interface CopyFileServiceInterface
{
    public function copyFile($sourcePath, $destinationPath);
}
