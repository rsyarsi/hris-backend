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

    public function indexMobile()
    {
        return $this->repository->indexMobile();
    }

    public function store(array $data)
    {
        $image = $data['image'] ?? null;
        if ($image && $image->isValid()) {
            // images upload
            $imageName = uniqid('image_') . '.' . $image->getClientOriginalExtension();
            $compressedImage = Image::load($image)->quality(50);
            $tempImagePath = tempnam(sys_get_temp_dir(), 'compressed_image');
            $compressedImage->save($tempImagePath);
            $imagePath = Storage::disk('s3')->putFileAs('hrd/informations/images', $tempImagePath, $imageName, 'public');
            $imageUrl = Storage::disk('s3')->url($imagePath);
            unlink($tempImagePath);
            $imageDisk = 's3';
        } else {
            $imagePath = null;
            $imageUrl = null;
            $imageDisk = null;
        }

        $file = $data['file'] ?? null;
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/informations/pdf', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
            $fileDisk = 's3';
        } else {
            $filePath = null;
            $fileUrl = null;
            $fileDisk = null;
        }
        $data = [
            'user_id' => auth()->id(),
            'name' => Str::upper($data['name']),
            'short_description' => $data['short_description'],
            'note' => $data['note'],
            'image_path' => $imagePath,
            'image_url' => $imageUrl,
            'image_disk' => $imageDisk,
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => $fileDisk,
        ];
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $image = $data['image'] ?? null;
        if ($image && $image->isValid()) {
            // images upload
            $imageName = uniqid('image_') . '.' . $image->getClientOriginalExtension();
            $compressedImage = Image::load($image)->quality(50);
            $tempImagePath = tempnam(sys_get_temp_dir(), 'compressed_image');
            $compressedImage->save($tempImagePath);
            $imagePath = Storage::disk('s3')->putFileAs('hrd/informations/images', $tempImagePath, $imageName, 'public');
            $imageUrl = Storage::disk('s3')->url($imagePath);
            unlink($tempImagePath);
            $imageDisk = 's3';
        } else {
            $imagePath = null;
            $imageUrl = null;
            $imageDisk = null;
        }

        $file = $data['file'] ?? null;
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/informations/pdf', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
            $fileDisk = 's3';
        } else {
            $filePath = null;
            $fileUrl = null;
            $fileDisk = null;
        }
        $data = [
            'user_id' => auth()->id(),
            'name' => Str::upper($data['name']),
            'short_description' => $data['short_description'],
            'note' => $data['note'],
            'image_path' => $imagePath,
            'image_url' => $imageUrl,
            'image_disk' => $imageDisk,
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => $fileDisk,
        ];
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
