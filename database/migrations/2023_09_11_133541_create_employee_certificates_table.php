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
        Schema::create('employee_certificates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id',26)->nullable();
            $table->string('name')->nullable();
            $table->string('institution_name')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_disk')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->integer('verified_user_Id')->nullable();
            $table->tinyInteger('is_extended')->nullable();
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
        Schema::dropIfExists('employee_certificates');
    }
};
