<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('tb_jadwal_lab')->onDelete('cascade');
            $table->string('judul', 100);
            $table->string('pesan', 255);
            $table->enum('status', ['baru','dibaca'])->default('baru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_notifikasi');
    }
};
