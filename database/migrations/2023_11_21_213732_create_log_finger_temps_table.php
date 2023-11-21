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
            $table->bigInteger('function');
            $table->bigInteger('snfinger');
            $table->integer('manual');
            $table->timestamp('user_manual');
            $table->timestamp('manual_date');
            $table->bigInteger('pin');
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
