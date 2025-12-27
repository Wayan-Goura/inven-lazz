<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';
    protected $fillable = ['transaksi_id', 'data_barang_id', 'jumlah'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function barang() // Gunakan huruf kecil agar konsisten
    {
        return $this->belongsTo(DataBarang::class, 'data_barang_id');
    }
}