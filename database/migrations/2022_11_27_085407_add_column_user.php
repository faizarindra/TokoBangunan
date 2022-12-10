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
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_telp')->after('password')->nullable();
            $table->date('dob')->after('no_telp')->nullable();
            $table->integer('jenis_kelamin')->after('dob')->nullable();
            $table->string('image')->after('jenis_kelamin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_telp');
            $table->dropColumn('dob');
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('image');
        });
    }
};
