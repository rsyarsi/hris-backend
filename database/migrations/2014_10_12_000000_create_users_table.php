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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('user_device_id')->nullable();
            $table->string('firebase_id')->nullable();
            $table->string('imei')->nullable();
            $table->string('ip')->nullable();
            $table->string('username')->nullable();
            $table->tinyInteger('administrator')->nullable()->default(0);
            $table->tinyInteger('hrd')->nullable()->default(0);
            $table->tinyInteger('manager')->nullable()->default(0);
            $table->tinyInteger('supervisor')->nullable()->default(0);
            $table->tinyInteger('pegawai')->nullable()->default(0);
            $table->tinyInteger('kabag')->nullable()->default(0);
            $table->tinyInteger('staff')->nullable()->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
