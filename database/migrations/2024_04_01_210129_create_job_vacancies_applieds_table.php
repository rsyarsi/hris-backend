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
        Schema::create('job_vacancies_applieds', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('set null');
            $table->string('candidate_id', 26)->nullable();
            $table->foreign('job_vacancy_id')->references('id')->on('job_vacancies')->onDelete('set null');
            $table->string('job_vacancy_id', 26)->nullable();
            $table->string('status', 150)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_vacancies_applieds');
    }
};
