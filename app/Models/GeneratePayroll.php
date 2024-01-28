<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneratePayroll extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'payroll_employees';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'employee_name', 'employee_id', 'employeement_id', 'employee_email', 'employee_status',
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
        'notes', 'file_name', 'file_path', 'file_url', 'created_at', 'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            dd($model->getAttributes());
            $model->created_at = now();
            $model->updated_at = now();
        });

        self::updating(function ($model) {
            dd($model->getAttributes());
            $model->updated_at = now();
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'employee_department_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'employee_unit_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Unit::class, 'employee_position_id', 'id');
    }
}
