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
        Schema::table('tb_pengajuan', function (Blueprint $table) {
            $table->string('hari', 20)->after('id_ruang')->nullable()->comment('Hari pemakaian lab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_pengajuan', function (Blueprint $table) {
            $table->dropColumn('hari');
        });
    }
};
