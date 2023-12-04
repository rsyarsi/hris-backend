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
        Schema::table('employees', function (Blueprint $table) {
            $table->bigInteger('rekening_number')->nullable();
            $table->bigInteger('bpjs_number')->nullable();
            $table->bigInteger('bpjstk_number')->nullable();
            $table->string('status_employee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['rekening_number', 'bpjs_number', 'bpjstk_number', 'status_employee']);
        });
    }
};
