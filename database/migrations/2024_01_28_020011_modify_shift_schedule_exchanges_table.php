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
        Schema::table('shift_schedule_exchanges', function (Blueprint $table) {
            $table->foreign('shift_awal_request_id')->references('id')->on('shifts')->onDelete('set null');
            $table->string('shift_awal_request_id', 26)->nullable();
            $table->foreign('exchange_shift_awal_id')->references('id')->on('shifts')->onDelete('set null');
            $table->string('exchange_shift_awal_id', 26)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_schedule_exchanges', function (Blueprint $table) {
            $table->dropForeign(['shift_awal_request_id']);
            $table->dropColumn('shift_awal_request_id');
            $table->dropForeign(['exchange_shift_awal_id']);
            $table->dropColumn('exchange_shift_awal_id');
        });
    }
};
