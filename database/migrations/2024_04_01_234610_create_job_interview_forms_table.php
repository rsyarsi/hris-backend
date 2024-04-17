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
            $table->timestamp('date')->nullable();
            $table->tinyInteger('communication_skills')->nullable();
            $table->tinyInteger('confidence_and_eye_contact')->nullable();
            $table->tinyInteger('coherent_problem_solving')->nullable();
            $table->tinyInteger('active_listening_and_feedback')->nullable();
            $table->tinyInteger('task_estimation_accuracy')->nullable();
            $table->tinyInteger('schedule_development')->nullable();
            $table->tinyInteger('resource_prioritization')->nullable();
            $table->tinyInteger('quick_problem_solving')->nullable();
            $table->tinyInteger('decision_making_under_uncertainty')->nullable();
            $table->tinyInteger('performance_under_pressure')->nullable();
            $table->tinyInteger('positive_situation_evaluation')->nullable();
            $table->tinyInteger('efficiency_improvement_solutions')->nullable();
            $table->tinyInteger('critical_problem_analysis')->nullable();
            $table->tinyInteger('personnel_motivation')->nullable();
            $table->tinyInteger('personal_performance_improvement')->nullable();
            $table->tinyInteger('strategic_communication')->nullable();
            $table->tinyInteger('extra_work_initiative')->nullable();
            $table->tinyInteger('goal_redirection')->nullable();
            $table->tinyInteger('ethical_behavior')->nullable();
            $table->tinyInteger('team_cooperation')->nullable();
            $table->tinyInteger('influencing_skills')->nullable();
            $table->tinyInteger('strategic_planning')->nullable();
            $table->tinyInteger('conflict_resolution')->nullable();
            $table->tinyInteger('additional_comments')->nullable();
            $table->enum('status', ['PENDING', 'HIRE', 'RECOMENDED-OTHER-POSITION', 'POSIBLE-INTEREST', 'REJECT'])->nullable()->default(null);
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
