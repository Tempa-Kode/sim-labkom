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
        Schema::table('tb_inventaris', function (Blueprint $table) {
            $table->dropForeign(['id_jenis']);
            $table->dropColumn(['kondisi', 'id_jenis']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_inventaris', function (Blueprint $table) {
            $table->string('kondisi')->nullable();
            $table->unsignedBigInteger('id_jenis')->nullable();
            $table->foreign('id_jenis')->references('id')->on('tb_jenis')->onDelete('set null');
        });
    }
};
