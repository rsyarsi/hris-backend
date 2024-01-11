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
            $table->date('birth_date')->nullable();
            $table->foreignId('marital_status_id')->nullable()->constrained('mmaritalstatuses')->nullOnDelete();
            $table->foreignId('religion_id')->nullable()->constrained('mreligions')->nullOnDelete();
            $table->string('blood_type',15)->nullable();
            $table->string('tax_identify_number',150)->nullable();
            $table->string('email',150)->nullable();
            $table->string('phone_number',20)->nullable();
            $table->string('phone_number_country',5)->nullable();
            $table->string('legal_address')->nullable();
            $table->string('legal_postal_code',10)->nullable();
            $table->foreignId('legal_province_id')->nullable()->constrained('indonesia_provinces')->nullOnDelete();
            $table->foreignId('legal_city_id')->nullable()->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('legal_district_id')->nullable()->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('legal_village_id')->nullable()->constrained('indonesia_villages')->nullOnDelete();
            $table->string('legal_home_phone_number',15)->nullable();
            $table->string('legal_home_phone_country',15)->nullable();
            $table->string('current_address')->nullable();
            $table->string('current_postal_code',10)->nullable();
            $table->foreignId('current_province_id')->nullable()->constrained('indonesia_provinces')->nullOnDelete();
            $table->foreignId('current_city_id')->nullable()->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('current_district_id')->nullable()->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('current_village_id')->nullable()->constrained('indonesia_villages')->nullOnDelete();
            $table->string('current_home_phone_number',15)->nullable();
            $table->string('current_home_phone_country',5)->nullable();
            $table->foreignId('status_employment_id')->nullable()->constrained('mstatusemployments')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('mpositions')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('munits')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('mdepartments')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->string('employment_number',50)->nullable()->unique();
            $table->timestamp('resigned_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->bigInteger('pin')->nullable();
            $table->bigInteger('rekening_number')->nullable();
            $table->bigInteger('bpjs_number')->nullable();
            $table->bigInteger('bpjstk_number')->nullable();
            $table->string('status_employee')->nullable();
            $table->foreign('shift_group_id')->references('id')->on('shift_groups')->onDelete('set null');
            $table->string('shift_group_id',26)->nullable();
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
