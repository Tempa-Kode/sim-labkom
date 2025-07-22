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
        Schema::create('tb_jadwal_lab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ruang_lab')
                  ->constrained('tb_ruang_lab')
                  ->onDelete('cascade');
            $table->string('hari', 50);
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->foreignId('id_dosen')
                  ->constrained('tb_dosen')
                  ->onDelete('cascade');
            $table->enum('status_ruang', ['digunakan', 'kosong'])->default('kosong');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_jadwal_lab');
    }
};
