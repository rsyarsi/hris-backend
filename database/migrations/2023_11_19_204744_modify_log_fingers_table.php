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
        Schema::table('log_fingers', function (Blueprint $table) {
            $table->dropColumn(['log_at', 'in_out']); // Drop the old columns

            // Add the new columns with timestamp with time zone
            $table->timestampTz('time_in')->nullable();
            $table->timestampTz('time_out')->nullable();
            $table->date('tgl_log')->nullable();
            $table->string('absen_type', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_fingers', function (Blueprint $table) {
            // Reverse the changes if needed
            $table->dropColumn(['time_in', 'time_out', 'tgl_log', 'absen_type']);

            // Recreate the old columns
            $table->timestamp('log_at')->nullable();
            $table->tinyInteger('in_out')->nullable();
        });
    }
};
