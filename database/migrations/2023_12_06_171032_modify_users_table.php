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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('administrator')->nullable()->default(0);
            $table->tinyInteger('hrd')->nullable()->default(0);
            $table->tinyInteger('manager')->nullable()->default(0);
            $table->tinyInteger('supervisor')->nullable()->default(0);
            $table->tinyInteger('pegawai')->nullable()->default(0);
            $table->tinyInteger('kabag')->nullable()->default(0);
            $table->tinyInteger('staff')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['administrator', 'hrd', 'manager', 'supervisor', 'pegawai', 'kabag', 'staff']);
        });
    }
};
