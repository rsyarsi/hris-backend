<?php
namespace App\Services\SuratPeringatan;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\SuratPeringatan\SuratPeringatanServiceInterface;
use App\Repositories\SuratPeringatan\SuratPeringatanRepositoryInterface;

class SuratPeringatanService implements SuratPeringatanServiceInterface
{
    private $repository;

    public function __construct(SuratPeringatanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $employeeId)
    {
        return $this->repository->index($perPage, $search, $employeeId);
    }

    public function store(array $data)
    {
        // Check if 'file' key exists and is not null
        $file = isset($data['file']) ? $data['file'] : null;
        $filePath = null;
        $fileUrl = null;
        $fileDisk = null;

        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/surat_peringatan', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
            $fileDisk = 's3';
        }

        $suratPeringatanData = [
            'employee_id' => $data['employee_id'],
            'date' => $data['date'],
            'no_surat' => $this->formatTextTitle($data['no_surat']),
            'type' => $this->formatTextTitle($data['type']),
            'jenis_pelanggaran' => $this->formatTextTitle($data['jenis_pelanggaran']),
            'keterangan' => $data['keterangan'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => $fileDisk,
            'user_created_id' => auth()->id(),
        ];

        return $this->repository->store($suratPeringatanData);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['user_created_id'] = auth()->id();
        $data['no_surat'] = $this->formatTextTitle($data['no_surat']);
        $data['type'] = $this->formatTextTitle($data['type']);
        $data['jenis_pelanggaran'] = $this->formatTextTitle($data['jenis_pelanggaran']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }
}
