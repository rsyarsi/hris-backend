<?php
namespace App\Services\GeneratePayroll;

use App\Mail\SlipGajiEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Services\GeneratePayroll\GeneratePayrollServiceInterface;
use App\Repositories\GeneratePayroll\GeneratePayrollRepositoryInterface;

class GeneratePayrollService implements GeneratePayrollServiceInterface
{
    private $repository;

    public function __construct(GeneratePayrollRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $unit)
    {
        return $this->repository->index($perPage, $search, $unit);
    }

    public function indexPeriod($period)
    {
        return $this->repository->indexPeriod($period);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function executeStoredProcedure($periodeAbsen, $periodePayroll)
    {
        return $this->repository->executeStoredProcedure($periodeAbsen, $periodePayroll);
    }

    public function generatePayrollEmployee($perPage, $search, $unit)
    {
        return $this->repository->generatePayrollEmployee($perPage, $search, $unit);
    }

    public function sendSlipGaji($id)
    {
        $item = $this->repository->show($id);
        if ($item) {
            $pdf = PDF::setOptions(['isRemoteEnabled' => TRUE, 'enable_javascript' => TRUE]);
            $pdf = PDF::loadView('pdf.slip_gaji.slip_gaji', compact('item'));
            $pdf->setPaper('a4', 'potrait');
            // Generate a unique filename
            $filename = $item->period_payroll . '-' . $item->employeement_id . '.pdf';
            // save to local
            // Storage::put('hrd/slip/' . $filename, $pdf->output());
            $s3FilePath = 'hrd/slip/' . $filename;
            Storage::disk('s3')->put($s3FilePath, $pdf->output());
            Storage::disk('s3')->setVisibility($s3FilePath, 'public');
            $fileUrl = Storage::disk('s3')->url($s3FilePath);
            // // Send email with attached PDF
            Mail::to($item->employee_email)->send(new SlipGajiEmail($item, $s3FilePath));

            return [
                'file_name' => $filename,
                'file_path' => $s3FilePath,
                'file_url' => $fileUrl,
                'email_address' => $item->employee_email
            ];
        }
        return null;
    }

    public function sendSlipGajiPeriod($period)
    {
        $results = [];

        $items = $this->repository->indexPeriod($period);

        foreach ($items as $item) {
            $pdf = PDF::setOptions(['isRemoteEnabled' => TRUE, 'enable_javascript' => TRUE]);
            $pdf = PDF::loadView('pdf.slip_gaji.slip_gaji', compact('item'));
            $pdf->setPaper('letter', 'potrait');

            // Generate a unique filename
            $filename = $item->period_payroll . '-' . $item->employeement_id . '.pdf';

            $s3FilePath = 'hrd/slip/' . $filename;
            Storage::disk('s3')->put($s3FilePath, $pdf->output());
            Storage::disk('s3')->setVisibility($s3FilePath, 'public');
            $fileUrl = Storage::disk('s3')->url($s3FilePath);

            // Send email with attached PDF
            Mail::to($item->employee_email)->send(new SlipGajiEmail($item, $s3FilePath));

            $results[] = [
                'file_name' => $filename,
                'file_path' => $s3FilePath,
                'file_url' => $fileUrl,
                'email_address' => $item->employee_email
            ];
        }

        return $results;
    }

}
