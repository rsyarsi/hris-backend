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
            $table->renameColumn('id_dead', 'is_dead');
            $table->date('birth_date')->nullable()->change();
            $table->dropColumn('employer_familiescol');
            $table->dropColumn('province_id');
            $table->dropColumn('village_id');
            $table->dropColumn('district_id');
            $table->dropColumn('city_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
