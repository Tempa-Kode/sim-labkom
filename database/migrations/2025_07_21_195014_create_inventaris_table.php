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
        Schema::create('tb_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 50);
            $table->string('kondisi', 100);
            $table->string('keterangan', 100)->nullable();
            $table->foreignId('id_jenis')->constrained('tb_jenis')->onDelete('cascade');
            $table->integer('jumlah');
            $table->foreignId('id_ruang')->constrained('tb_ruang_lab')->onDelete('cascade');
            $table->foreignId('id_pengguna')->constrained('tb_pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_inventaris');
    }
};
