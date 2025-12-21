<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;
use App\Models\DataBarang;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';

    protected $fillable = [
        'transaksi_id', 
        'data_barang_id', 
        'jumlah',
    ];

    /**
     * Relasi Belongs To
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    /**
     * Relasi Belongs To
     */
    public function Barang()
    {
        return $this->belongsTo(DataBarang::class, 'data_barang_id');
    }
}