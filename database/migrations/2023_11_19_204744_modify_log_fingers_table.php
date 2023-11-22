<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE log_fingers ALTER COLUMN id TYPE UUID USING id::UUID');

        Schema::table('log_fingers', function (Blueprint $table) {
            $table->dropColumn(['log_at', 'in_out']); // Drop the old columns
            $table->timestampTz('time_in')->nullable();
            $table->timestampTz('time_out')->nullable();
            $table->date('tgl_log')->nullable();
            $table->string('absen_type', 15)->nullable();
            $table->integer('function')->nullable();
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
            $table->dropColumn('id');
            $table->dropColumn(['time_in', 'time_out', 'tgl_log', 'absen_type', 'function']);
            // Recreate the old columns
            $table->ulid('id')->primary();
            $table->timestamp('log_at')->nullable();
            $table->tinyInteger('in_out')->nullable();
        });
    }
};
