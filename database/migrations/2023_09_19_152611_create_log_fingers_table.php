<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id',26)->nullable();
            $table->string('code_sn_finger',45)->nullable();
            $table->timestamp('datetime')->nullable();
            $table->tinyInteger('manual')->nullable();
            $table->foreignId('user_manual_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('input_manual_at')->nullable();
            $table->string('code_pin',45)->nullable();
            $table->timestampTz('time_in')->nullable();
            $table->timestampTz('time_out')->nullable();
            $table->date('tgl_log')->nullable();
            $table->tinyInteger('in_out')->nullable();
            $table->timestamp('log_at')->nullable();
            $table->string('absen_type', 15)->nullable();
            $table->integer('function')->nullable();
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
