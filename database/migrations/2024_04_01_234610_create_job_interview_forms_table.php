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
        Schema::create('job_interview_forms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('set null');
            $table->string('candidate_id', 26)->nullable();
            $table->foreign('job_vacancy_id')->references('id')->on('job_vacancies')->onDelete('set null');
            $table->string('job_vacancy_id', 26)->nullable();
            $table->foreign('interviewer_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('interviewer_id', 26)->nullable();
            $table->foreign('job_vacancies_applied_id')->references('id')->on('job_vacancies_applieds')->onDelete('set null');
            $table->string('job_vacancies_applied_id', 26)->nullable();
            $table->date('date')->nullable();
            $table->text('communication_skills')->nullable();
            $table->text('confidence_and_eye_contact')->nullable();
            $table->text('coherent_problem_solving')->nullable();
            $table->text('active_listening_and_feedback')->nullable();
            $table->text('task_estimation_accuracy')->nullable();
            $table->text('schedule_development')->nullable();
            $table->text('resource_prioritization')->nullable();
            $table->text('quick_problem_solving')->nullable();
            $table->text('decision_making_under_uncertainty')->nullable();
            $table->text('performance_under_pressure')->nullable();
            $table->text('positive_situation_evaluation')->nullable();
            $table->text('efficiency_improvement_solutions')->nullable();
            $table->text('critical_problem_analysis')->nullable();
            $table->text('personnel_motivation')->nullable();
            $table->text('personal_performance_improvement')->nullable();
            $table->text('strategic_communication')->nullable();
            $table->text('extra_work_initiative')->nullable();
            $table->text('goal_redirection')->nullable();
            $table->text('ethical_behavior')->nullable();
            $table->text('team_cooperation')->nullable();
            $table->text('influencing_skills')->nullable();
            $table->text('strategic_planning')->nullable();
            $table->text('conflict_resolution')->nullable();
            $table->text('additional_comments')->nullable();
            $table->enum('status', ['HIRE', 'RECOMENDED-OTHER-POSITION', 'POSIBLE-INTEREST', 'REJECT'])->nullable();
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
        Schema::dropIfExists('job_interview_forms');
    }
};
