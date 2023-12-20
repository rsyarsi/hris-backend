<?php

namespace App\Repositories\GeneratePayroll;

use Carbon\Carbon;
use App\Models\GeneratePayroll;
use Illuminate\Support\Facades\DB;
use App\Repositories\GeneratePayroll\GeneratePayrollRepositoryInterface;
use App\Services\Employee\EmployeeServiceInterface;

class GeneratePayrollRepository implements GeneratePayrollRepositoryInterface
{
    private $model;
    private $employeeService;
    private $field =
    [
        'id', 'employee_name', 'employee_id', 'employeement_id', 'employee_email', 'employee_status',
        'employee_id_number', 'employee_tax_number', 'employee_rekening_number', 'employee_department_id',
        'employee_department_name', 'employee_unit_id', 'employee_unit_name', 'employee_position_id',
        'employee_position_name', 'employee_sex', 'employee_tax_status', 'employee_start_date',
        'employee_day_works', 'employee_fix_gapok', 'employee_fix_transport', 'employee_fix_uangmakan',
        'employee_fix_tunjangankemahalan', 'fix_income_total', 'employee_tunjangan_hdm',
        'employee_tunjangan_jabatan', 'employee_tunjangan_dinasmalam', 'employee_tunjangan_tunjanganppr',
        'employee_tunjangan_intensifkhusus', 'employee_tunjangan_extrafooding', 'employee_tunjangan_lembur',
        'tunjangan_total', 'salary_bruto', 'kelebihanpotongan', 'liability_companies_jkk', 'liability_companies_jkm',
        'liability_companies_jht', 'liability_companies_jp', 'liability_companies_bpjskesehatan', 'liability_companies_total',
        'liability_employee_potongan', 'liability_employee_jht', 'liability_employee_jp', 'liability_employee_bpjskesehatan',
        'liability_employee_pph21', 'liability_employee_total', 'salary_total', 'salary_total_before_zakat',
        'zakat', 'salary_after_zakat', 'period_payroll', 'thr', 'liability_employee_foods', 'liability_employee_absens',
        'notes', 'file_name', 'file_path', 'file_url'
    ];

    public function __construct(GeneratePayroll $model, EmployeeServiceInterface $employeeService)
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search = null, $unit = null, $period = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number', 'unit_id')->with('unit:id,name');
                        }
                    ])
                    ->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(employee_name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        if ($unit) {
            $query->whereHas('employee', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
        }
        if ($period) {
            $query->where('period_payroll', $period);
        }
        return $query->orderBy('period_payroll', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $generatePayroll = $this->model->where('id', $id)->first($this->field);
        return $generatePayroll ? $generatePayroll : $generatePayroll = null;
    }

    public function update($id, $data)
    {
        $generatePayroll = $this->model->find($id);
        if ($generatePayroll) {
            $generatePayroll->update($data);
            return $generatePayroll;
        }
        return null;
    }

    public function destroy($id)
    {
        $generatePayroll = $this->model->find($id);
        if ($generatePayroll) {
            $generatePayroll->delete();
            return $generatePayroll;
        }
        return null;
    }

    public function executeStoredProcedure($periodeAbsen, $periodePayroll)
    {
        $periodeAbsen = Carbon::parse($periodeAbsen);
        $periodePayroll = Carbon::parse($periodePayroll);
        $employees = $this->employeeService->employeeActive(999999999, null);
        // return $employees;
        $now = Carbon::now();
        foreach ($employees as $item) {
            $result = DB::select('CALL generatepayroll(?, ?, ?, ?, ?)', [(string)$item->id, $periodeAbsen->format('Y-m'), $periodePayroll->format('Y-m'), $periodePayroll->format('Y'), $now->toDateString()]);
        }
        if ($result) {
            return $result;
        }
        return null;
    }

    public function generatePayrollEmployee($perPage, $search = null, $employeeId = null)
    {
        $query = $this->model
                    ->where('employee_id', $employeeId)
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number', 'unit_id')->with('unit:id,name');
                        }
                    ])
                    ->select($this->field);
        return $query->orderBy('period_payroll', 'DESC')->paginate($perPage);
    }

    public function indexPeriod($period)
    {
        return $this->model
                    ->where('period_payroll', $period)
                    ->where('employee_email', '!=', null)
                    ->get($this->field);
    }
}
