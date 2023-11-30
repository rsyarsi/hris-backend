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
        Schema::create('deductions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->decimal('nilai', 18, 2)->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('tenor')->nullable();
            $table->string('period', 45)->nullable();
            $table->decimal('pembayaran', 18, 2)->nullable();
            $table->decimal('sisa', 18, 2)->nullable();
            $table->string('kode_lunas')->nullable();
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
        Schema::dropIfExists('deductions');
    }
};
