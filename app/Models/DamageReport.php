<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'user_id',
        'quantity',
        'jenis_kerusakan',
        'notes',
        'tanggal',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'quantity' => 'integer',
    ];

    /**
     * Relasi ke Asset (master barang)
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Relasi ke User (petugas pelapor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
