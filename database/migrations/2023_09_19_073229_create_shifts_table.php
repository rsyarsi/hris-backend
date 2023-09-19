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
        Schema::create('shifts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('shift_group_id')->references('id')->on('shift_groups')->onDelete('set null');
            $table->string('shift_group_id',26)->nullable();
            $table->string('code',45)->nullable();
            $table->string('name',150)->nullable();
            $table->string('in_time',45)->nullable();
            $table->string('out_time',45)->nullable();
            $table->integer('finger_in_less')->nullable();
            $table->integer('finger_in_more')->nullable();
            $table->integer('finger_out_less')->nullable();
            $table->integer('finger_out_more')->nullable();
            $table->tinyInteger('night_shift')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->foreignId('user_created_id')->constrained('users')->nullOnDelete();
            $table->foreignId('user_updated_id')->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('shifts');
    }
};
