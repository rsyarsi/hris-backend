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

    public function index($perPage, $search, $unit, $period)
    {
        return $this->repository->index($perPage, $search, $unit, $period);
    }

    public function indexMobile($employeeId)
    {
        return $this->repository->indexMobile($employeeId);
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
        $rapel = $data['kelebihanpotongan'];
        // pokok
        $employeeFixGapok = $data['employee_fix_gapok'];
        $employeeFixTransport = $data['employee_fix_transport'];
        $employeeFixUangmakan = $data['employee_fix_uangmakan'];
        $employeeFixTunjangankemahalan = $data['employee_fix_tunjangankemahalan'];
        $fixIncomeTotal = (int)$employeeFixGapok + (int)$employeeFixTransport +
            (int)$employeeFixUangmakan + (int)$employeeFixTunjangankemahalan;
        $data['fix_income_total'] = $fixIncomeTotal; // oke

        // tunjangan
        $employeeTunjanganHdm = $data['employee_tunjangan_hdm'];
        $employeeTunjanganJabatan = $data['employee_tunjangan_jabatan'];
        $employeeTunjanganDinasmalam = $data['employee_tunjangan_dinasmalam'];
        $employeeTunjanganTunjanganppr = $data['employee_tunjangan_tunjanganppr'];
        $employeeTunjanganIntensifkhusus = $data['employee_tunjangan_intensifkhusus'];
        $employeeTunjanganExtrafooding = $data['employee_tunjangan_extrafooding'];
        $employeeTunjanganLembur = $data['employee_tunjangan_lembur'];
        $totalTunjangan = (int)$employeeTunjanganHdm + (int) $employeeTunjanganJabatan +
            (int)$employeeTunjanganDinasmalam + (int)$employeeTunjanganTunjanganppr +
            (int)$employeeTunjanganIntensifkhusus + (int)$employeeTunjanganExtrafooding +
            (int)$employeeTunjanganLembur;
        $data['tunjangan_total'] = $totalTunjangan; // oke

        // liability company
        $liabilityCompaniesJkk = $data['liability_companies_jkk'];
        $liabilityCompaniesJkm = $data['liability_companies_jkm'];
        $liabilityCompaniesJht = $data['liability_companies_jht'];
        $liabilityCompaniesJp = $data['liability_companies_jp'];
        $liabilityCompaniesBpjskesehatan = $data['liability_companies_bpjskesehatan'];
        $liabilityCompaniesTotal = (int)$liabilityCompaniesJkk + (int) $liabilityCompaniesJkm +
            (int)$liabilityCompaniesJht + (int)$liabilityCompaniesJp +
            (int)$liabilityCompaniesBpjskesehatan;
        $data['liability_companies_total'] = $liabilityCompaniesTotal; //oke

        // liability pekerja
        $liabilityEmployeeJht = $data['liability_employee_jht'];
        $liabilityEmployeeJp = $data['liability_employee_jp'];
        $liabilityEmployeePotongan = $data['liability_employee_potongan'];
        $liabilityEmployeeBpjskesehatan = $data['liability_employee_bpjskesehatan'];
        $liabilityEmployeePph21 = $data['liability_employee_pph21'];
        $liabilityEmployeeTotal = (int)$liabilityEmployeeJht + (int) $liabilityEmployeeJp +
            (int)$liabilityEmployeePotongan + (int)$liabilityEmployeeBpjskesehatan +
            (int)$liabilityEmployeePph21;
        $data['liability_employee_total'] = $liabilityEmployeeTotal; // oke

        $gajiBruto = $totalTunjangan + $fixIncomeTotal;
        $data['salary_bruto'] = $gajiBruto;
        $data['salary_total'] = $gajiBruto + (int)$rapel + $liabilityCompaniesTotal + $liabilityEmployeeTotal;
        $salaryTotalBeforeZakat = ($gajiBruto + (int)$rapel) - $liabilityEmployeeTotal;
        $data['salary_total_before_zakat'] = $salaryTotalBeforeZakat;

        // zakat
        if ($salaryTotalBeforeZakat >= 7083000) {
            $zakat = ($salaryTotalBeforeZakat * 2.5) / 100;
        } else {
            $zakat = 0;
        }
        $data['zakat'] = $zakat;
        $salaryAfterZakat = $salaryTotalBeforeZakat - $zakat;
        $data['salary_after_zakat'] = $salaryAfterZakat;

        // update to aws
        $item = $this->repository->update($id, $data);

        $pdf = PDF::setOptions(['isRemoteEnabled' => TRUE, 'enable_javascript' => TRUE]);
        $pdf = PDF::loadView('pdf.slip_gaji.slip_gaji', compact('item'));
        $pdf->setPaper('a4', 'potrait');
        // Generate a unique filename
        $filename = $item->period_payroll . '-' . $item->employeement_id . '.pdf';
        $s3FilePath = 'hrd/slip/' . $filename;
        Storage::disk('s3')->put($s3FilePath, $pdf->output());
        Storage::disk('s3')->setVisibility($s3FilePath, 'public');
        $fileUrl = Storage::disk('s3')->url($s3FilePath);
        // update data
        $dataFile['file_name'] = $filename;
        $dataFile['file_path'] = $s3FilePath;
        $dataFile['file_url'] = $fileUrl;

        return $this->repository->update($id, $dataFile);
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
            // update data
            $data['file_name'] = $filename;
            $data['file_path'] = $s3FilePath;
            $data['file_url'] = $fileUrl;
            $this->repository->update($id, $data);
            // Send email with attached PDF
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
            // update data
            $data['file_name'] = $filename;
            $data['file_path'] = $s3FilePath;
            $data['file_url'] = $fileUrl;
            $this->repository->update($item->id, $data);
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

    public function slipGajiMobile($id)
    {
        return $this->repository->slipGajiMobile($id);
    }
}
