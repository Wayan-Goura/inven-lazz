<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataBarang extends Model
{
    /**
     * The table associated with the model.
     *
     * Laravel's default pluralization would expect `data_barangs`,
     * so we explicitly set it to match the migration.
     */
    protected $table = 'data_barangs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_barang',
        'merek',
        'category_id',
        'k_barang',
        'jml_stok',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'jml_stok' => 'integer',
    ];

    /**
     * The category this item belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Detail transaksi records for this item.
     */
    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'data_barang_id');
    }

    /**
     * Return records for this item.
     */
    public function barangRetruns(): HasMany
    {
        return $this->hasMany(BarangReturn::class, 'barang_id');
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'data_barang_id');
    }

}
