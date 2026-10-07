<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'stock_good',
        'stock_damaged',
        'keterangan',
    ];

    protected $casts = [
        'stock_good'    => 'integer',
        'stock_damaged' => 'integer',
    ];

    /**
     * Relasi ke IncomingGoodsLog
     */
    public function incomingGoodsLogs()
    {
        return $this->hasMany(IncomingGoodsLog::class);
    }

    /**
     * Relasi ke DamageReport
     */
    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    /**
     * Total stok keseluruhan (baik + rusak)
     */
    public function getTotalStockAttribute(): int
    {
        return $this->stock_good + $this->stock_damaged;
    }
}
