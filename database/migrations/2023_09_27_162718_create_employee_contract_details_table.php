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
        Schema::create('employee_contract_details', function (Blueprint $table) {
            $table->id();
            $table->foreign('employee_contract_id')->references('id')->on('employee_contracts')->onDelete('set null');
            $table->string('employee_contract_id',26)->nullable();
            $table->foreignId('payroll_component_id')->nullable()->constrained('mpayrollcomponents')->nullOnDelete();
            $table->decimal('nominal', 18, 0)->nullable();
            $table->tinyInteger('active')->nullable();
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
        Schema::dropIfExists('employee_contract_details');
    }
};
