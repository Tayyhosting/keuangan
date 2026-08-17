<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
        $table->id();
        $table->enum('jenis', ['pemasukan', 'pengeluaran']);
        $table->enum('sumber_dana', ['saldo', 'cash']);
        $table->decimal('nominal', 12, 2);
        $table->text('keterangan')->nullable();
        $table->timestamp('tanggal')->nullable(); // Kolom waktu transaksi
        $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};