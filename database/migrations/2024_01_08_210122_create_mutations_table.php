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
        Schema::create('mutations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_created_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->foreignId('before_unit_id')->nullable()->constrained('munits')->nullOnDelete();
            $table->foreignId('after_unit_id')->nullable()->constrained('munits')->nullOnDelete();
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->string('no_sk')->nullable();
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
        Schema::dropIfExists('mutations');
    }
};
