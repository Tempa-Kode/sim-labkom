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
        Schema::create('tb_rekapan_jadwal_lab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_aslab')->constrained('tb_pengguna')->onDelete('cascade')->comment('ID aslab yang input jadwal');
            $table->foreignId('id_jadwal_lab')->constrained('tb_jadwal_lab')->onDelete('cascade')->comment('ID jadwal lab yang diinput');
            $table->enum('aksi', ['tambah', 'edit', 'hapus', 'ubah_status'])->comment('Jenis aksi yang dilakukan');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan jika ada');
            $table->date('tanggal_aksi')->comment('Tanggal aksi dilakukan');
            $table->time('waktu_aksi')->comment('Waktu aksi dilakukan');
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
        Schema::dropIfExists('tb_rekapan_jadwal_lab');
    }
};
