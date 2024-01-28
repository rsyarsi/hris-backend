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
        Schema::table('leaves', function (Blueprint $table) {
            $table->foreign('shift_awal_id')->references('id')->on('shifts')->onDelete('set null');
            $table->string('shift_awal_id', 26)->nullable();
            $table->foreign('shift_schedule_id')->references('id')->on('shift_schedules')->onDelete('set null');
            $table->string('shift_schedule_id', 26)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['shift_awal_id']);
            $table->dropColumn('shift_awal_id');
            $table->dropForeign(['shift_schedule_id']);
            $table->dropColumn('shift_schedule_id');
        });
    }
};
