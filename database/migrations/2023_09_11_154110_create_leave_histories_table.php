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
        Schema::create('leave_histories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('set null');
            $table->string('leave_id',26)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->longText('user_agent')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('leave_histories');
    }
};
