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
        Schema::create('human_resources_tests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('set null');
            $table->string('candidate_id', 26)->nullable();
            $table->string('name', 150)->nullable();
            $table->string('applied_position', 150)->nullable();
            $table->date('date')->nullable();
            $table->text('source_of_info')->nullable();
            $table->text('motivation')->nullable();
            $table->text('self_assessment')->nullable();
            $table->text('desired_position')->nullable();
            $table->text('coping_with_department_change')->nullable();
            $table->text('previous_job_challenges')->nullable();
            $table->text('reason_for_resignation')->nullable();
            $table->text('conflict_management')->nullable();
            $table->text('stress_management')->nullable();
            $table->text('overtime_availability')->nullable();
            $table->text('handling_complaints')->nullable();
            $table->text('salary_expectation')->nullable();
            $table->text('benefits_facilities')->nullable();
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
        Schema::dropIfExists('human_resources_tests');
    }
};
