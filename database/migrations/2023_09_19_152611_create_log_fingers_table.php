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
        Schema::create('log_fingers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->timestamp('log_at')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id',26)->nullable();
            $table->tinyInteger('in_out')->nullable();
            $table->string('code_sn_finger',45)->nullable();
            $table->timestamp('datetime')->nullable();
            $table->tinyInteger('manual')->nullable();
            $table->integer('user_manual_id')->nullable();
            $table->timestamp('input_manual_at')->nullable();
            $table->string('code_pin',45)->nullable();
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
        Schema::dropIfExists('log_fingers');
    }
};
