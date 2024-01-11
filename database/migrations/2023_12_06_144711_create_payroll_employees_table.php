<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_employees', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('employee_name')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->integer('employeement_id')->nullable();
            $table->string('employee_email')->nullable();
            $table->string('employee_status')->nullable();
            $table->string('employee_id_number')->nullable();
            $table->string('employee_tax_number')->nullable();
            $table->string('employee_rekening_number')->nullable();
            $table->foreignId('employee_department_id')->nullable()->constrained('mdepartments')->nullOnDelete();
            $table->string('employee_department_name')->nullable();
            $table->foreignId('employee_unit_id')->nullable()->constrained('munits')->nullOnDelete();
            $table->string('employee_unit_name')->nullable();
            $table->foreignId('employee_position_id')->nullable()->constrained('mpositions')->nullOnDelete();
            $table->string('employee_position_name')->nullable();
            $table->string('employee_sex')->nullable();
            $table->string('employee_tax_status')->nullable();
            $table->date('employee_start_date')->nullable();
            $table->string('employee_day_works')->nullable();
            $table->decimal('employee_fix_gapok', 18, 0)->nullable();
            $table->decimal('employee_fix_transport', 18, 0)->nullable();
            $table->decimal('employee_fix_uangmakan', 18, 0)->nullable();
            $table->decimal('employee_fix_tunjangankemahalan', 18, 0)->nullable();
            $table->decimal('fix_income_total', 18, 0)->nullable();
            $table->decimal('employee_tunjangan_hdm', 18, 0)->nullable();
            $table->decimal('employee_tunjangan_jabatan', 18, 0)->nullable();
            $table->decimal('employee_tunjangan_dinasmalam', 18, 0)->nullable();
            $table->decimal('employee_tunjangan_tunjanganppr', 18, 0)->nullable();
            $table->decimal('employee_tunjangan_intensifkhusus', 18, 0)->nullable();
            $table->decimal('employee_tunjangan_extrafooding', 18, 0)->nullable();
            $table->decimal('employee_tunjangan_lembur', 18, 0)->nullable();
            $table->decimal('tunjangan_total', 18, 0)->nullable();
            $table->decimal('salary_bruto', 18, 0)->nullable();
            $table->decimal('kelebihanpotongan', 18, 0)->nullable();
            $table->decimal('liability_companies_jkk', 18, 0)->nullable();
            $table->decimal('liability_companies_jkm', 18, 0)->nullable();
            $table->decimal('liability_companies_jht', 18, 0)->nullable();
            $table->decimal('liability_companies_jp', 18, 0)->nullable();
            $table->decimal('liability_companies_bpjskesehatan', 18, 0)->nullable();
            $table->decimal('liability_companies_total', 18, 0)->nullable();
            $table->decimal('liability_employee_potongan', 18, 0)->nullable();
            $table->decimal('liability_employee_jht', 18, 0)->nullable();
            $table->decimal('liability_employee_jp', 18, 0)->nullable();
            $table->decimal('liability_employee_bpjskesehatan', 18, 0)->nullable();
            $table->decimal('liability_employee_pph21', 18, 0)->nullable();
            $table->decimal('liability_employee_total', 18, 0)->nullable();
            $table->decimal('salary_total', 18, 0)->nullable();
            $table->decimal('salary_total_before_zakat', 18, 0)->nullable();
            $table->decimal('zakat', 18, 0)->nullable();
            $table->decimal('salary_after_zakat', 18, 0)->nullable();
            $table->string('period_payroll', 50)->nullable();
            $table->decimal('thr', 18, 2)->nullable();
            $table->decimal('liability_employee_foods', 18, 2)->nullable();
            $table->decimal('liability_employee_absens', 18, 2)->nullable();
            $table->string('notes')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_employees');
    }
};
