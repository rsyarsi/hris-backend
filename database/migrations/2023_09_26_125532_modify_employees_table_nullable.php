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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('employment_number', 36)->nullable()->unique()->change();
            $table->date('birth_date')->nullable()->change();
            $table->unsignedBigInteger('legal_province_id')->nullable()->change();
            $table->unsignedBigInteger('legal_city_id')->nullable()->change();
            $table->unsignedBigInteger('legal_district_id')->nullable()->change();
            $table->unsignedBigInteger('legal_village_id')->nullable()->change();
            $table->unsignedBigInteger('current_province_id')->nullable()->change();
            $table->unsignedBigInteger('current_city_id')->nullable()->change();
            $table->unsignedBigInteger('current_district_id')->nullable()->change();
            $table->unsignedBigInteger('current_village_id')->nullable()->change();
            $table->unsignedBigInteger('status_employment_id')->nullable()->change();
            $table->unsignedBigInteger('position_id')->nullable()->change();
            $table->unsignedBigInteger('unit_id')->nullable()->change();
            $table->unsignedBigInteger('department_id')->nullable()->change();
            $table->unsignedBigInteger('user_id')->nullable()->change();
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
