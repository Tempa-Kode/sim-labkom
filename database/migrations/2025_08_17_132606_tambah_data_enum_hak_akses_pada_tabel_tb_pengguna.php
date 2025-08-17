<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_pengguna', function (Blueprint $table) {
            DB::statement("ALTER TABLE tb_pengguna MODIFY COLUMN hak_akses ENUM('admin', 'aslab', 'dosen', 'kepala_lab')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_pengguna', function (Blueprint $table) {
            DB::statement("ALTER TABLE tb_pengguna MODIFY COLUMN hak_akses ENUM('admin', 'aslab', 'dosen')");
        });
    }
};
