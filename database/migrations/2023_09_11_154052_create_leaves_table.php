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
        Schema::create('leaves', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id',26)->nullable();
            $table->foreignId('leave_type_id')->nullable()->constrained('leave_types')->nullOnDelete();
            $table->foreignId('leave_status_id')->nullable()->constrained('leave_statuses')->nullOnDelete();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->integer('duration')->nullable();
            $table->text('note')->nullable();
            $table->integer('quantity_cuti_awal')->nullable();
            $table->integer('sisa_cuti')->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_disk')->nullable();
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
        Schema::dropIfExists('leaves');
    }
};
