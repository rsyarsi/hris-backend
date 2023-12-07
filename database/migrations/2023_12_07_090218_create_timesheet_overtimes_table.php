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
        Schema::create('timesheet_overtimes', function (Blueprint $table) {
            $table->uuid('id')->primary()->defaultRaw('uuid_generate_v4()');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->string('employee_name')->nullable();
            $table->string('unitname')->nullable();
            $table->string('positionname')->nullable();
            $table->string('departmenname')->nullable();
            $table->string('overtime_type')->nullable();
            $table->string('realisasihours')->nullable();
            $table->date('schedule_date_in_at')->nullable();
            $table->string('schedule_time_in_at')->nullable();
            $table->date('schedule_date_out_at')->nullable();
            $table->string('schedule_time_out_at')->nullable();
            $table->date('date_in_at')->nullable();
            $table->string('time_in_at')->nullable();
            $table->date('date_out_at')->nullable();
            $table->string('time_out_at')->nullable();
            $table->string('jamlemburawal')->nullable();
            $table->decimal('jamlemburconvert', 18, 2)->nullable();
            $table->string('jamlembur')->nullable();
            $table->decimal('tuunjangan', 18, 2)->nullable();
            $table->decimal('uanglembur', 18, 2)->nullable();
            $table->string('period', 7)->nullable();
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
        Schema::dropIfExists('timesheet_overtimes');
    }
};
