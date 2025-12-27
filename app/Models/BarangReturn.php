<?php

namespace App\Models;

use App\Models\Category;
use App\Models\DataBarang;
use Illuminate\Database\Eloquent\Model;

class BarangReturn extends Model
{
    public $fillable = [
        'barang_id',
        'category_id',
        'tanggal_return',
        'jumlah_return',
        'deskripsi',
        'user_id',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(DataBarang::class);
    }

}