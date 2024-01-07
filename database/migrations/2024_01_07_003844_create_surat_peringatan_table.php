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
        Schema::create('surat_peringatan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_created_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_id', 26)->nullable();
            $table->date('date')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('type')->nullable();
            $table->string('jenis_pelanggaran')->nullable();
            $table->string('keterangan')->nullable();
            $table->tinyInteger('batal')->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_disk')->nullable();
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
        Schema::dropIfExists('surat_peringatan');
    }
};
