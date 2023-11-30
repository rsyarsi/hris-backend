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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('shift_group_id')->references('id')->on('shift_groups')->onDelete('set null');
            $table->string('shift_group_id',26)->nullable();
            $table->foreign('kabid_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('kabid_id',26)->nullable();
            $table->foreign('kabag_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('kabag_id',26)->nullable();
            $table->foreign('kains_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('kains_id',26)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['shift_group_id', 'kabid_id', 'kabag_id', 'kains_id']);
        });
    }
};
