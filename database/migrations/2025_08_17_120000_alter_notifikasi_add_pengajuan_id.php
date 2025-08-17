<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_notifikasi', function (Blueprint $table) {
            // Jadikan jadwal_id nullable, dan tambahkan pengajuan_id nullable
            if (Schema::hasColumn('tb_notifikasi', 'jadwal_id')) {
                try {
                    $table->dropForeign(['jadwal_id']);
                } catch (\Throwable $e) {
                    // ignore if FK not exists
                }
                $table->unsignedBigInteger('jadwal_id')->nullable()->change();
                $table->foreign('jadwal_id')->references('id')->on('tb_jadwal_lab')->onDelete('cascade');
            }
            if (!Schema::hasColumn('tb_notifikasi', 'pengajuan_id')) {
                $table->foreignId('pengajuan_id')->nullable()->constrained('tb_pengajuan')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_notifikasi', function (Blueprint $table) {
            if (Schema::hasColumn('tb_notifikasi', 'pengajuan_id')) {
                try { $table->dropForeign(['pengajuan_id']); } catch (\Throwable $e) {}
                $table->dropColumn('pengajuan_id');
            }
            if (Schema::hasColumn('tb_notifikasi', 'jadwal_id')) {
                try { $table->dropForeign(['jadwal_id']); } catch (\Throwable $e) {}
                $table->unsignedBigInteger('jadwal_id')->nullable(false)->change();
                $table->foreign('jadwal_id')->references('id')->on('tb_jadwal_lab')->onDelete('cascade');
            }
        });
    }
};
