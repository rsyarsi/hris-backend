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
        Schema::create('shift_schedule_exchanges', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employe_requested_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employe_requested_id', 26)->nullable();
            $table->date('shift_schedule_date_requested')->nullable();
            $table->foreign('shift_schedule_request_id')->references('id')->on('shift_schedules')->onDelete('set null');
            $table->string('shift_schedule_request_id', 26)->nullable();
            $table->string('shift_schedule_code_requested', 50)->nullable();
            $table->string('shift_schedule_name_requested')->nullable();
            $table->string('shift_schedule_time_from_requested', 50)->nullable();
            $table->string('shift_schedule_time_end_requested', 50)->nullable();
            $table->string('shift_exchange_type', 50)->nullable();
            $table->foreign('to_employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('to_employee_id', 26)->nullable();
            $table->date('shift_schedule_date_to')->nullable();
            $table->foreign('to_shift_schedule_id')->references('id')->on('shift_schedules')->onDelete('set null');
            $table->string('to_shift_schedule_id', 26)->nullable();
            $table->string('to_shift_schedule_code', 50)->nullable();
            $table->string('to_shift_schedule_name')->nullable();
            $table->string('to_shift_schedule_time_from', 50)->nullable();
            $table->string('to_shift_schedule_time_end', 50)->nullable();
            $table->foreign('exchange_employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('exchange_employee_id', 26)->nullable();
            $table->date('exchange_date')->nullable();
            $table->foreign('exchange_shift_schedule_id')->references('id')->on('shift_schedules')->onDelete('set null');
            $table->string('exchange_shift_schedule_id', 26)->nullable();
            $table->string('exchange_shift_schedule_code', 50)->nullable();
            $table->string('exchange_shift_schedule_name')->nullable();
            $table->string('exchange_shift_schedule_time_from', 50)->nullable();
            $table->string('exchange_shift_schedule_time_end', 50)->nullable();
            $table->date('date_created')->nullable();
            $table->date('date_updated')->nullable();
            $table->foreignId('user_created_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_updated_id')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('cancel')->nullable();
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
        Schema::dropIfExists('shift_exchanges');
    }
};
