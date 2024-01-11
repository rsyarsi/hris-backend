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
        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->foreign('shift_exchange_id')->nullable()->references('id')->on('shift_schedule_exchanges')->onDelete('set null');
            $table->string('shift_exchange_id',26)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->dropForeign(['shift_exchange_id']);
            $table->dropColumn('shift_exchange_id');
        });
    }
};
