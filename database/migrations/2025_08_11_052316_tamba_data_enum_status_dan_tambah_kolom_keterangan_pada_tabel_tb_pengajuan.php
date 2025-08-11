<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add keterangan column
        DB::statement('ALTER TABLE tb_pengajuan ADD COLUMN keterangan TEXT NULL');

        // Modify enum column with raw SQL
        DB::statement("ALTER TABLE tb_pengajuan MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'dibatalkan') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop keterangan column
        DB::statement('ALTER TABLE tb_pengajuan DROP COLUMN keterangan');

        // Revert status enum to original values
        DB::statement("ALTER TABLE tb_pengajuan MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};
