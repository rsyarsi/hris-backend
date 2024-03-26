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
        Schema::create('education_background_candidates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('set null');
            $table->string('candidate_id', 26)->nullable();
            $table->foreignId('education_id')->nullable()->constrained('meducations')->nullOnDelete();
            $table->string('institution_name', 150)->nullable();
            $table->string('major', 150)->nullable();
            $table->string('started_year', 4)->nullable();
            $table->string('ended_year', 4)->nullable();
            $table->decimal('final_score', 18, 2)->nullable();
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
        Schema::dropIfExists('education_background_candidates');
    }
};
