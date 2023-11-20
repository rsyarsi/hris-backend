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
        Schema::create('generate_absen', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('period', 50)->nullable();
            $table->date('date')->nullable();
            $table->string('day', 50)->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('set null');
            $table->string('shift_id', 26)->nullable();
            $table->date('date_in_at')->nullable();
            $table->string('time_in_at', 50)->nullable();
            $table->date('date_out_at')->nullable();
            $table->string('time_out_at', 50)->nullable();
            $table->date('schedule_date_in_at')->nullable();
            $table->string('schedule_time_in_at', 50)->nullable();
            $table->date('schedule_date_out_at')->nullable();
            $table->string('schedule_time_out_at', 50)->nullable();
            $table->decimal('telat', 18, 2)->nullable();
            $table->decimal('pa', 18, 2)->nullable();
            $table->tinyInteger('holiday')->nullable();
            $table->tinyInteger('night')->nullable();
            $table->tinyInteger('national_holiday')->nullable();
            $table->text('note')->nullable();
            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('set null');
            $table->string('leave_id', 26)->nullable();
            $table->foreignId('leave_type_id')->nullable()->constrained('leave_types')->nullOnDelete();
            $table->string('leave_time_at', 50)->nullable();
            $table->string('leave_out_at', 50)->nullable();
            $table->string('schedule_leave_time_at', 50)->nullable();
            $table->string('schedule_leave_out_at', 50)->nullable();
            $table->foreign('overtime_id')->references('id')->on('overtimes')->onDelete('set null');
            $table->string('overtime_id', 26)->nullable();
            $table->date('overtime_at')->nullable();
            $table->string('overtime_time_at', 50)->nullable();
            $table->string('overtime_out_at', 50)->nullable();
            $table->string('schedule_overtime_time_at', 50)->nullable();
            $table->string('schedule_overtime_out_at', 50)->nullable();
            $table->decimal('ot1', 18, 2)->nullable();
            $table->decimal('ot2', 18, 2)->nullable();
            $table->decimal('ot3', 18, 2)->nullable();
            $table->decimal('ot4', 18, 2)->nullable();
            $table->tinyInteger('manual')->nullable();
            $table->foreignId('user_manual_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('input_manual_at')->nullable();
            $table->integer('lock')->nullable();
            $table->string('gp_in', 50)->nullable();
            $table->string('gp_out', 50)->nullable();
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
        Schema::dropIfExists('generate_absen');
    }
};
