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
        Schema::table('log_generate_absen', function (Blueprint $table) {
            $table->text('message')->nullable();
            $table->boolean('success')->nullable();
            $table->tinyInteger('code')->nullable();
            $table->text('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_generate_absen', function (Blueprint $table) {
            $table->dropColumn(['message', 'success', 'code', 'data']);
        });
    }
};
