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
        Schema::table('human_resources_tests', function (Blueprint $table) {
            $table->foreign('job_vacancy_id')->references('id')->on('job_vacancies')->onDelete('set null');
            $table->string('job_vacancy_id', 26)->nullable();
            $table->dropColumn(['name', 'applied_position']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('human_resources_tests', function (Blueprint $table) {
            $table->dropForeign(['job_vacancy_id']);
            $table->dropColumn('job_vacancy_id');
        });
    }
};
