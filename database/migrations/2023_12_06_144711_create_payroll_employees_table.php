<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->uuid('id')->primary()->default('uuid_generate_v4');
            $table->string('employee_name')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->integer('employeement_id')->nullable();
            $table->string('employee_email')->nullable();
            $table->string('employee_status')->nullable();
            $table->string('employee_id_number')->nullable();
            $table->string('employee_tax_number')->nullable();
            $table->string('employee_rekening_number')->nullable();
            $table->foreign('employee_department_id')->references('id')->on('mdepartments')->onDelete('set null');
            $table->string('employee_department_name')->nullable();
            $table->foreign('employee_unit_id')->references('id')->on('munits')->onDelete('set null');
            $table->string('employee_unit_name')->nullable();
            $table->foreign('employee_position_id')->references('id')->on('mpositions')->onDelete('set null');
            $table->string('employee_position_name')->nullable();
            $table->string('employee_sex')->nullable();
            $table->string('employee_tax_status')->nullable();
            $table->string('employee_start_date')->nullable();
            $table->string('employee_day_works')->nullable();
            $table->string('employee_fix_gapok')->nullable();
            $table->string('employee_fix_transport')->nullable();
            $table->string('employee_fix_uangmakan')->nullable();
            $table->string('employee_fix_tunjangankemahalan')->nullable();
            $table->string('fix_income_total')->nullable();
            $table->string('employee_tunjangan_hdm')->nullable();
            $table->string('employee_tunjangan_jabatan')->nullable();
            $table->string('employee_tunjangan_dinasmalam')->nullable();
            $table->string('employee_tunjangan_tunjanganppr')->nullable();
            $table->string('employee_tunjangan_intensifkhusus')->nullable();
            $table->string('employee_tunjangan_extrafooding')->nullable();
            $table->string('employee_tunjangan_lembur')->nullable();
            $table->string('tunjangan_total')->nullable();
            $table->string('salary_bruto')->nullable();
            $table->string('kelebihanpotongan')->nullable();
            $table->string('liability_companies_jkk')->nullable();
            $table->string('liability_companies_jkm')->nullable();
            $table->string('liability_companies_jht')->nullable();
            $table->string('liability_companies_jp')->nullable();
            $table->string('liability_companies_bpjskesehatan')->nullable();
            $table->string('liability_companies_total')->nullable();
            $table->string('liability_employee_potongan')->nullable();
            $table->string('liability_employee_jht')->nullable();
            $table->string('liability_employee_jp')->nullable();
            $table->string('liability_employee_bpjskesehatan')->nullable();
            $table->string('liability_employee_pph21')->nullable();
            $table->string('liability_employee_total')->nullable();
            $table->string('salary_total')->nullable();
            $table->string('salary_total_before_zakat')->nullable();
            $table->string('zakat')->nullable();
            $table->string('salary_after_zakat')->nullable();
            $table->string('period_payroll', 50)->nullable();
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
