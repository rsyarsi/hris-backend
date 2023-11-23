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
        Schema::create('log_finger_temps', function (Blueprint $table) {
            $table->id();
            $table->date('date_log')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->bigInteger('function')->nullable();
            $table->bigInteger('snfinger')->nullable();
            $table->timestamp('absen')->nullable();
            $table->integer('manual')->nullable();
            $table->string('user_manual', 150)->nullable();
            $table->date('manual_date')->nullable();
            $table->bigInteger('pin')->nullable();
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
        Schema::dropIfExists('log_finger_temps');
    }
};
