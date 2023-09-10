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
        Schema::create('employer_families', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id',26)->nullable();
            $table->string('name',150)->nullable();
            $table->foreignId('relationship_id')->nullable()->constrained('mrelationships')->nullOnDelete();
            $table->tinyInteger('as_emergency')->nullable();
            $table->tinyInteger('id_dead')->nullable();
            $table->date('birth_date');
            $table->string('phone',20)->nullable();
            $table->string('phone_country',5)->nullable();
            $table->string('employer_familiescol',45)->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code',10)->nullable();
            $table->foreignId('province_id')->constrained('indonesia_provinces')->nullOnDelete();
            $table->foreignId('city_id')->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('district_id')->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('village_id')->constrained('indonesia_villages')->nullOnDelete();
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
        Schema::dropIfExists('employer_families');
    }
};
