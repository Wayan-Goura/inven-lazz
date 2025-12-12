<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // Nama tabel yang sesuai dengan migrasi
    protected $table = 'transaksis';

    // Kolom yang dapat diisi massal
    protected $fillable = [
        'kode_transaksi',
        'tanggal_transaksi',
        'user_id',
        'tipe_transaksi',
        'total_barang',
    ];

    // Konversi tipe data
    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    /**
     * Relasi One-to-Many: 
     */
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    /**
     * Relasi Belongs To: 
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}