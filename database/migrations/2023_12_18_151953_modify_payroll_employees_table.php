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
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->decimal('thr', 18, 2)->nullable();
            $table->decimal('liability_employee_foods', 18, 2)->nullable();
            $table->decimal('liability_employee_absens', 18, 2)->nullable();
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->dropColumn(['thr', 'liability_employee_foods', 'liability_employee_absens', 'notes']);
        });
    }
};
