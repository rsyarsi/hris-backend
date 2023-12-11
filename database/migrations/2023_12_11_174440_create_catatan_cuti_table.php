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
        Schema::create('catatan_cuti', function (Blueprint $table) {
            $table->ulid()->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->integer('quantity_awal')->nullable();
            $table->integer('quantity_akhir')->nullable();
            $table->integer('quantity_in')->nullable();
            $table->integer('quantity_out')->nullable();
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('batal')->nullable();
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
        Schema::dropIfExists('catatan_cuti');
    }
};
