<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangReturn extends Model
{
    use HasFactory;

    // Gunakan protected agar mass assignment diizinkan oleh Laravel
    protected $fillable = [
        'barang_id',
        'category_id',
        'tanggal_return',
        'jumlah_return',
        'deskripsi',
        'user_id',
        'pending_perubahan',
        'is_disetujui',
    ];

    protected $casts = [
        'pending_perubahan' => 'array',
        'is_disetujui' => 'boolean',
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke DataBarang (Pastikan foreign key barang_id tertulis)
    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'barang_id');
    }
}