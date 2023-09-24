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
            $table->foreignId('province_id')->nullable()->constrained('indonesia_provinces', 'id')->onUpdate('cascade')->onDelete('set null')->after('postal_code');
            $table->foreignId('city_id')->nullable()->constrained('indonesia_cities', 'id')->onUpdate('cascade')->onDelete('set null')->after('province_id');
            $table->foreignId('district_id')->nullable()->constrained('indonesia_districts', 'id')->onUpdate('cascade')->onDelete('set null')->after('city_id');
            $table->foreignId('village_id')->nullable()->constrained('indonesia_villages', 'id')->onUpdate('cascade')->onDelete('set null')->after('district_id');
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
            //
        });
    }
};
