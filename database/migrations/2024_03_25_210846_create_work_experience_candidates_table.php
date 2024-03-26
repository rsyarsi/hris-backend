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
        Schema::create('work_experience_candidates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('set null');
            $table->string('candidate_id', 26)->nullable();
            $table->string('company', 150)->nullable();
            $table->string('position', 150)->nullable();
            $table->string('location')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->text('job_description')->nullable();
            $table->text('reason_for_resignation')->nullable();
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
        Schema::dropIfExists('work_experience_candidates');
    }
};
