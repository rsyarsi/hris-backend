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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id',26)->nullable();
            $table->foreign('kabag_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('kabag_id',26)->nullable();
            $table->foreign('supervisor_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('supervisor_id',26)->nullable();
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('manager_id',26)->nullable();
            $table->foreignId('department_id')->nullable()->constrained('mdepartments')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('munits')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('mpositions')->nullOnDelete();
            $table->string('transaction_number',32)->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('sk_number',50)->nullable();
            $table->foreign('shift_group_id')->references('id')->on('shift_groups')->onDelete('set null');
            $table->string('shift_group_id',26)->nullable();
            $table->decimal('umk', 18, 2)->nullable();
            $table->foreignId('contract_type_id')->nullable()->constrained('mcontracttypes')->nullOnDelete();
            $table->integer('day')->nullable();
            $table->integer('hour')->nullable();
            $table->integer('hour_per_day')->nullable();
            $table->decimal('istirahat_overtime', 18, 2)->nullable();
            $table->decimal('vot1', 18, 0)->nullable();
            $table->decimal('vot2', 18, 0)->nullable();
            $table->decimal('vot3', 18, 0)->nullable();
            $table->decimal('vot4', 18, 0)->nullable();
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
        Schema::dropIfExists('employee_contracts');
    }
};
