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
        Schema::table('employer_families', function (Blueprint $table) {
            // Rename the 'id_dead' column to 'is_dead'
            $table->renameColumn('id_dead', 'is_dead');

            // Remove the 'employer_familiescol' column
            $table->dropColumn('employer_familiescol');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employer_families', function (Blueprint $table) {
            // Reverse the operations if needed
            $table->renameColumn('is_dead', 'id_dead');
            $table->string('employer_familiescol', 45)->nullable();
        });
    }
};
