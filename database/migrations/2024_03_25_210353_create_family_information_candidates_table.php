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
        Schema::create('family_information_candidates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('set null');
            $table->string('candidate_id', 26)->nullable();
            $table->foreignId('relationship_id')->nullable()->constrained('mrelationships')->nullOnDelete();
            $table->string('name', 150)->nullable();
            $table->foreignId('sex_id')->nullable()->constrained('msexs')->nullOnDelete();
            $table->string('birth_place', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('education_id')->nullable()->constrained('meducations')->nullOnDelete();
            $table->foreignId('job_id')->nullable()->constrained('mjobs')->nullOnDelete();
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
        Schema::dropIfExists('family_information_candidates');
    }
};
