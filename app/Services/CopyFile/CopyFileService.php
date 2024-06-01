<?php

namespace App\Services\CopyFile;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\CopyFile\CopyFileServiceInterface;


class CopyFileService implements CopyFileServiceInterface
{

    public function copyFile($sourcePath, $destinationPath)
    {
        try {
            $disk = Storage::disk('s3');

            // Check if the source file exists
            if (!$disk->exists($sourcePath)) {
                throw new \Exception("Source file does not exist.");
            }

            // Copy the file to the new location
            $disk->copy($sourcePath, $destinationPath);

            return true;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("File copy failed: " . $e->getMessage());

            return false;
        }
    }
}
