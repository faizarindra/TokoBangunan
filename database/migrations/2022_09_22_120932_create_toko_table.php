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
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('nama_toko');
            $table->text('deskripsi_toko');
            $table->string('gambar_toko');
            $table->text('alamat_toko');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('prefix');
            $table->integer('id_provinsi');
            $table->string('nama_provinsi');
            $table->integer('id_kota');
            $table->string('nama_kota');
            $table->integer('id_kecamatan');
            $table->string('nama_kecamatan');
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
        Schema::dropIfExists('toko');
    }
};
