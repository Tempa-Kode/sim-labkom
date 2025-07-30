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
        Schema::create('tb_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dosen')->constrained('tb_dosen')->onDelete('cascade');
            $table->foreignId('id_ruang')->constrained('tb_ruang_lab')->onDelete('cascade');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_pemakaian');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_pengajuan');
    }
};
