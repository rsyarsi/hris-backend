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
        Schema::create('employees', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name',150)->nullable();
            $table->foreignId('legal_identity_type_id')->nullable()->constrained('midentitytypes')->nullOnDelete();
            $table->string('legal_identity_number',150)->nullable();
            $table->string('family_card_number',150)->nullable();
            $table->foreignId('sex_id')->nullable()->constrained('msexs')->nullOnDelete();
            $table->string('birth_place',50)->nullable();
            $table->date('birth_date');
            $table->foreignId('marital_status_id')->nullable()->constrained('mmaritalstatuses')->nullOnDelete();
            $table->foreignId('religion_id')->nullable()->constrained('mreligions')->nullOnDelete();
            $table->string('blood_type',15)->nullable();
            $table->string('tax_identify_number',150)->nullable();
            $table->string('email',150)->nullable();
            $table->string('phone_number',20)->nullable();
            $table->string('phone_number_country',5)->nullable();
            $table->string('legal_address')->nullable();
            $table->string('legal_postal_code',10)->nullable();
            $table->foreignId('legal_province_id')->constrained('indonesia_provinces')->nullOnDelete();
            $table->foreignId('legal_city_id')->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('legal_district_id')->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('legal_village_id')->constrained('indonesia_villages')->nullOnDelete();
            $table->string('legal_home_phone_number',15)->nullable();
            $table->string('legal_home_phone_country',15)->nullable();
            $table->string('current_address')->nullable();
            $table->string('current_postal_code',10)->nullable();
            $table->foreignId('current_province_id')->constrained('indonesia_provinces')->nullOnDelete();
            $table->foreignId('current_city_id')->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('current_district_id')->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('current_village_id')->constrained('indonesia_villages')->nullOnDelete();
            $table->string('current_home_phone_number',15)->nullable();
            $table->string('current_home_phone_country',5)->nullable();
            $table->foreignId('status_employment_id')->constrained('mstatusemployments')->nullOnDelete();
            $table->foreignId('position_id')->constrained('mpositions')->nullOnDelete();
            $table->foreignId('unit_id')->constrained('munits')->nullOnDelete();
            $table->foreignId('department_id')->constrained('mdepartments')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->string('employment_number',36)->nullable();
            $table->timestamp('resigned_at')->nullable();
            $table->foreignId('user_id')->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('employees');
    }
};
