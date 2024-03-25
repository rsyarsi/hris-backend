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
        Schema::create('candidates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('first_name', 150);
            $table->string('middle_name', 150)->nullable();
            $table->string('last_name', 150)->nullable();
            $table->foreignId('sex_id')->nullable()->constrained('msexs')->nullOnDelete();
            $table->foreignId('legal_identity_type_id')->nullable()->constrained('midentitytypes')->nullOnDelete();
            $table->string('legal_identity_number', 150)->nullable();
            $table->text('legal_address')->nullable();
            $table->text('current_address')->nullable();
            $table->string('home_phone_number', 20)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('birth_place', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->foreignId('marital_status_id')->nullable()->constrained('mmaritalstatuses')->nullOnDelete();
            $table->foreignId('ethnic_id')->nullable()->constrained('methnics')->nullOnDelete();
            $table->foreignId('religion_id')->nullable()->constrained('mreligions')->nullOnDelete();
            $table->string('tax_identify_number', 150)->nullable();
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->foreignId('candidate_account_id')->nullable()->constrained('candidate_accounts')->nullOnDelete();
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
        Schema::dropIfExists('candidates');
    }
};
