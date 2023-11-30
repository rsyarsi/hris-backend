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
        Schema::table('mpayrollcomponents', function (Blueprint $table) {
            $table->string('group_component_payroll')->nullable();
            $table->integer('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mpayrollcomponents', function (Blueprint $table) {
            $table->dropColumn(['group_component_payroll', 'order']);
        });
    }
};
