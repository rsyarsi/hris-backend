<?php
namespace App\Services\EmployeeCertificate;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\EmployeeCertificate\EmployeeCertificateServiceInterface;
use App\Repositories\EmployeeCertificate\EmployeeCertificateRepositoryInterface;

class EmployeeCertificateService implements EmployeeCertificateServiceInterface
{
    private $repository;

    public function __construct(EmployeeCertificateRepositoryInterface $repository)
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
            // Upload the file to AWS S3 storage
            $filePath = $file->store('employee_certificates', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }

        $data = [
            'employee_id' => $data['employee_id'],
            'name' => $data['name'],
            'institution_name' => $data['institution_name'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
            'verified_at' => $data['verified_at'],
            'verified_user_Id' => $data['verified_user_Id'],
            'is_extended' => $data['is_extended'],
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
            $filePath = $file->store('employee_certificates', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }
        $legalityData = [
            'employee_id' => $data['employee_id'],
            'name' => $data['name'],
            'institution_name' => $data['institution_name'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
            'verified_at' => $data['verified_at'],
            'verified_user_Id' => $data['verified_user_Id'],
            'is_extended' => $data['is_extended'],
        ];
        return $this->repository->update($id, $legalityData);
    }

    public function destroy($id)
    {
        // $data = $this->repository->show($id);
        // if ($data['file_path']) {
        //     $filePath = str_replace(url('storage/'), '', $data['file_path']);
        //     Storage::disk('public')->delete($filePath);
        // }
        return $this->repository->destroy($id);
    }

    // public function UploadtoAWS($fiesNaames)
    // {
    //     $file_name = storage_path() . "/app/".$fiesNaames; //LABORATORIUM_RJJP020823-0108.pdf
    //     $source =   $file_name;
    //     $s3Client = new S3Client([
    //         'version' => 'latest',
    //         'region'  => env('AWS_DEFAULT_REGION'),
    //         'http'    => ['verify' => false],
    //         'credentials' => [
    //             'key'    => env('AWS_ACCESS_KEY_ID'),
    //             'secret' => env('AWS_SECRET_ACCESS_KEY')
    //         ]
    //       ]);
    //       $keyaws = 'hasilmcu/';
    //       $bucket = env('AWS_BUCKET');
    //         $key = basename($file_name);
    //         $result = $s3Client->putObject([
    //             'Bucket' => $bucket,
    //             'Key'    => $keyaws . $key,
    //             'Body'   => fopen($source, 'r'),
    //             'ACL'    => 'public-read', // make file 'public',
    //         ]);
    //         $awsurl = $result->get('ObjectURL');
    //         Storage::delete($file_name);
    //         return $awsurl;
    // }
}
