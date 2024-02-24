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
        Schema::create('order_overtimes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('employee_staff_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_staff_id', 26)->nullable();
            $table->foreign('employee_entry_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_entry_id', 26)->nullable();
            $table->foreignId('user_created_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->text('note_order')->nullable();
            $table->text('note_overtime')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('holiday')->nullable();
            $table->decimal('duration', 18, 2)->nullable();
            $table->enum('status', ['OPEN', 'APPROVE', 'REJECT']);
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
        Schema::dropIfExists('order_overtimes');
    }
};
