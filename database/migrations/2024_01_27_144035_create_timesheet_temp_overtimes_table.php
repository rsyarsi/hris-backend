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
        Schema::create('timesheet_temp_overtimes', function (Blueprint $table) {
            $table->uuid('generate_absensi_id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->string('name')->nullable();
            $table->string('namaunit')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('overtime_type')->nullable();
            $table->integer('overtime_hours')->nullable();
            $table->date('schedule_date_in_at')->nullable();
            $table->string('schedule_time_in_at')->nullable();
            $table->date('schedule_date_out_at')->nullable();
            $table->string('schedule_time_out_at')->nullable();
            $table->date('date_in_at')->nullable();
            $table->string('time_in_at')->nullable();
            $table->date('date_out_at')->nullable();
            $table->string('time_out_at')->nullable();
            $table->string('waktulemburawal')->nullable();
            $table->string('waktulemburakhir')->nullable();
            $table->integer('jamlemburangka')->nullable();
            $table->integer('jamshiftkerja')->nullable();
            $table->integer('tunjangan')->nullable();
            $table->string('periode')->nullable();
            $table->string('jamlemburafterpotongan')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('lamaharilibur')->nullable();
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
        Schema::dropIfExists('timesheet_temp_overtimes');
    }
};
