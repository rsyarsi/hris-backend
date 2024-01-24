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
        Schema::table('promotion_demotions', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained('mdepartments')->nullOnDelete();
            $table->foreign('shift_group_id')->references('id')->on('shift_groups')->onDelete('set null');
            $table->string('shift_group_id',26)->nullable();
            $table->foreign('kabag_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('kabag_id',26)->nullable();
            $table->foreign('supervisor_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('supervisor_id',26)->nullable();
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('manager_id',26)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_demotions', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['shift_group_id']);
            $table->dropForeign(['kabag_id']);
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['manager_id']);
            $table->dropColumn('department_id');
            $table->dropColumn('shift_group_id');
            $table->dropColumn('kabag_id');
            $table->dropColumn('supervisor_id');
            $table->dropColumn('manager_id');
        });
    }
};
