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
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id',26)->nullable();
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('set null');
            $table->string('shift_id',26)->nullable();
            $table->date('date')->nullable();
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->string('late_note', 150)->nullable();
            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('set null');
            $table->string('leave_id',26)->nullable();
            $table->foreignId('user_exchange_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('user_exchange_at')->nullable();
            $table->foreignId('created_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('setup_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('setup_at')->nullable();
            $table->string('period', 32)->nullable();
            $table->string('leave_note', 50)->nullable();
            $table->tinyInteger('holiday')->nullable();
            $table->tinyInteger('night')->nullable();
            $table->tinyInteger('national_holiday')->nullable();
            $table->string('absen_type', 20)->nullable();
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
        Schema::dropIfExists('shift_schedules');
    }
};
