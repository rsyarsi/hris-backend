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
        DB::statement('ALTER TABLE generate_absen ALTER COLUMN id TYPE UUID USING id::UUID');

        Schema::table('generate_absen', function (Blueprint $table) {
            $table->string('employment_id', 100)->nullable();
            $table->string('overtime_type', 100)->nullable();
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
            $table->dropColumn(['employment_id', 'overtime_type']);
            if (!Schema::hasColumn('generate_absen', 'id')) {
                $table->ulid('id')->primary();
            }
        });
    }
};
