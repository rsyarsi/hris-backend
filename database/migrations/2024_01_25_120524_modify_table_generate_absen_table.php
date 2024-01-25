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
        Schema::table('generate_absen', function (Blueprint $table) {
            $table->foreign('shift_schedule_id')->references('id')->on('shift_schedules')->onDelete('set null');
            $table->string('shift_schedule_id',26)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generate_absen', function (Blueprint $table) {
            $table->dropForeign(['shift_schedule_id']);
        });
    }
};
