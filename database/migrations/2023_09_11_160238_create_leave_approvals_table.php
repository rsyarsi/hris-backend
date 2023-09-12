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
        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('set null');
            $table->string('leave_id',26)->nullable();
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('manager_id',26)->nullable();
            $table->string('action')->nullable();
            $table->timestamp('action_at')->nullable();
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
        Schema::dropIfExists('leave_approvals');
    }
};
