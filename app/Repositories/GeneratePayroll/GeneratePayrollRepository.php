<?php

namespace App\Repositories\GeneratePayroll;

use Carbon\Carbon;
use App\Models\GeneratePayroll;
use Illuminate\Support\Facades\DB;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Deduction\DeductionServiceInterface;
use App\Repositories\GeneratePayroll\GeneratePayrollRepositoryInterface;

class GeneratePayrollRepository implements GeneratePayrollRepositoryInterface
{
    private $model;
    private $employeeService;
    private $deductionService;
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

    public function __construct(
        GeneratePayroll $model,
        EmployeeServiceInterface $employeeService,
        DeductionServiceInterface $deductionService,
    )
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
        $this->deductionService = $deductionService;
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
            $query->whereRaw('LOWER(employee_name) LIKE ?', ["%".strtolower($search)."%"])
                    ->orWhere('employeement_id', 'like', '%' . $search . '%');
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

    public function indexMobile($employeeId = null)
    {
        $currentYear = now()->year;
        $query = DB::table('payroll_employees')
            ->select([
                'payroll_employees.id',
                'payroll_employees.employee_name',
                'payroll_employees.employee_id',
                'payroll_employees.employeement_id',
                'payroll_employees.employee_email',
                'payroll_employees.employee_status',
                'payroll_employees.employee_id_number',
                'payroll_employees.employee_tax_number',
                'payroll_employees.employee_rekening_number',
                'payroll_employees.employee_department_id',
                'payroll_employees.employee_department_name',
                'payroll_employees.employee_position_id',
                'payroll_employees.employee_position_name',
                'payroll_employees.employee_sex',
                'payroll_employees.employee_tax_status',
                'payroll_employees.employee_start_date',
                'payroll_employees.employee_day_works',
                'payroll_employees.employee_fix_gapok',
                'payroll_employees.employee_fix_transport',
                'payroll_employees.employee_fix_uangmakan',
                'payroll_employees.employee_fix_tunjangankemahalan',
                'payroll_employees.fix_income_total',
                'payroll_employees.employee_tunjangan_hdm',
                'payroll_employees.employee_tunjangan_jabatan',
                'payroll_employees.employee_tunjangan_dinasmalam',
                'payroll_employees.employee_tunjangan_tunjanganppr',
                'payroll_employees.employee_tunjangan_intensifkhusus',
                'payroll_employees.employee_tunjangan_extrafooding',
                'payroll_employees.employee_tunjangan_lembur',
                'payroll_employees.tunjangan_total',
                'payroll_employees.salary_bruto',
                'payroll_employees.kelebihanpotongan',
                'payroll_employees.liability_companies_jkk',
                'payroll_employees.liability_companies_jkm',
                'payroll_employees.liability_companies_jht',
                'payroll_employees.liability_companies_jp',
                'payroll_employees.liability_companies_bpjskesehatan',
                'payroll_employees.liability_companies_total',
                'payroll_employees.liability_employee_potongan',
                'payroll_employees.liability_employee_jht',
                'payroll_employees.liability_employee_jp',
                'payroll_employees.liability_employee_bpjskesehatan',
                'payroll_employees.liability_employee_pph21',
                'payroll_employees.liability_employee_total',
                'payroll_employees.salary_total',
                'payroll_employees.salary_total_before_zakat',
                'payroll_employees.zakat',
                'payroll_employees.salary_after_zakat',
                'payroll_employees.period_payroll',
                'payroll_employees.thr',
                'payroll_employees.liability_employee_foods',
                'payroll_employees.liability_employee_absens',
                'payroll_employees.notes',
                'payroll_employees.file_name',
                'payroll_employees.file_path',
                'payroll_employees.file_url'
            ])
            ->leftJoin('employees', 'payroll_employees.employee_id', '=', 'employees.id')
            ->where(DB::raw('LEFT(payroll_employees.period_payroll, 4)'), $currentYear)
            ->orderBy('payroll_employees.period_payroll', 'DESC');

        if ($employeeId) {
            $query->where('payroll_employees.employee_id', $employeeId);
        }

        return $query->get();
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $generatePayroll = $this->model
                                ->with([
                                    'employee' => function ($query) {
                                        $query->select('id', 'name', 'email', 'employment_number');
                                    },
                                ])
                                ->where('id', $id)
                                ->first($this->field);
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
        $now = Carbon::now();

        foreach ($employees as $item) {
            $result = DB::select('CALL generatepayroll(?, ?, ?, ?, ?)', [
                (string)$item->id,
                $periodeAbsen->format('Y-m'),
                $periodePayroll->format('Y-m'),
                $periodePayroll->format('Y'),
                $now->toDateString()
            ]);

            // Update notes in generate_payroll based on deductions
            $deductions = DB::table('deductions')
                            ->where('employee_id', $item->id)
                            ->where('period', $periodePayroll->format('Y-m'))
                            ->get();

            $notes = [];
            foreach ($deductions as $deduction) {
                $keteranganValue = $deduction->keterangan; // Replace with the actual field name
                $nilaiValue = $deduction->nilai; // Replace with the actual field name
                $notes[] = "$keteranganValue: $nilaiValue";
            }

            if (!empty($notes)) {
                $notesValue = implode(PHP_EOL, $notes);
                DB::table('payroll_employees')
                    ->where('employee_id', $item->id)
                    ->where('period_payroll', $periodePayroll->format('Y-m'))
                    ->update(['notes' => $notesValue]);
            }
        }

        return $result;
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
