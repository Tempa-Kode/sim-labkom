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
        Schema::table('tb_jadwal_lab', function (Blueprint $table) {
            $table->unsignedBigInteger('dibuat_oleh')->nullable()->after('status_ruang')->comment('ID user yang membuat jadwal (aslab)');
            $table->text('alasan_kosong')->nullable()->after('dibuat_oleh')->comment('Alasan ruang kosong (dosen sakit, jam lewat, dll)');

            // Foreign key untuk dibuat_oleh
            $table->foreign('dibuat_oleh')->references('id')->on('tb_pengguna')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_jadwal_lab', function (Blueprint $table) {
            $table->dropForeign(['dibuat_oleh']);
            $table->dropColumn(['dibuat_oleh', 'alasan_kosong']);
        });
    }
};
