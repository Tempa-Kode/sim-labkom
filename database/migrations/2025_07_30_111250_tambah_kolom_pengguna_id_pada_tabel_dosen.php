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
        Schema::table('tb_dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pengguna')->nullable()->after('id');
            $table->foreign('id_pengguna')->references('id')->on('tb_pengguna')->onDelete('set null');

            $table->string('foto', 255)->nullable()->after('nama_dosen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_dosen', function (Blueprint $table) {
            $table->dropForeign(['pengguna_id']);
            $table->dropColumn('pengguna_id');

            $table->dropColumn('foto');
        });
    }
};
