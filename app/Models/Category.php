<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'nama_category',
        'deskripsi',
    ];

    public function dataBarangs()
    {
        return $this->hasMany(DataBarang::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
