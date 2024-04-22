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
        Schema::table('job_interview_forms', function (Blueprint $table) {
            $table->date('date_interview')->nullable();
            $table->text('additional_comments')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_interview_forms', function (Blueprint $table) {
            $table->dropColumn(['date_interview']);
        });
    }
};
