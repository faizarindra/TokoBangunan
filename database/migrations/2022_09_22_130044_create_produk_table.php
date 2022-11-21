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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->integer('id_toko');
            $table->integer('id_user');
            $table->integer('id_kategori');
            $table->integer('id_merk');
            $table->string('nama_produk');
            $table->text('deskripsi');
            $table->string('gambar_produk');
            $table->string('harga_produk');
            $table->integer('stok_raw');
            $table->string('satuan_produk');
            $table->string('prefix');
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
        Schema::dropIfExists('produk');
    }
};
