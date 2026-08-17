<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    
    // Kolom yang boleh diisi
    protected $fillable = [
        'jenis',
        'sumber_dana',
        'nominal',
        'keterangan',
        'tanggal'
    ];
}