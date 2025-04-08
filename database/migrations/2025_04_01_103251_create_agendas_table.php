<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendasTable extends Migration
{
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->json('foto')->nullable(); // Untuk banyak foto
            $table->string('penanggung_jawab');
            $table->string('keterangan');
            $table->string('jabatan'); // Kolom untuk jabatan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agendas');
    }
}
