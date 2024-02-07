<?php
namespace App\Services\Information;

use Spatie\Image\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Information\InformationServiceInterface;
use App\Repositories\Information\InformationRepositoryInterface;

class InformationService implements InformationServiceInterface
{
    private $repository;

    public function __construct(InformationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $file = $data['file'];
        if ($file && $file->isValid()) {
            $fileName = uniqid('image_') . '.' . $file->getClientOriginalExtension();

            // Compress the image before uploading
            $compressedImage = Image::load($file)->quality(50);

            // Save the compressed image to a temporary file
            $tempFilePath = tempnam(sys_get_temp_dir(), 'compressed_image');
            $compressedImage->save($tempFilePath);

            // Upload the temporary file to AWS S3 storage
            $filePath = Storage::disk('s3')->putFileAs('hrd/informations', $tempFilePath, $fileName, 'public');

            // Get the public URL of the uploaded file
            $fileUrl = Storage::disk('s3')->url($filePath);

            // Clean up the temporary file
            unlink($tempFilePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }
        $data = [
            'user_id' => auth()->id(),
            'name' => Str::upper($data['name']),
            'note' => $data['note'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $file = $data['file'];
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/informations', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }
        $data = [
            'user_id' => auth()->id(),
            'name' => Str::upper($data['name']),
            'note' => $data['note'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
