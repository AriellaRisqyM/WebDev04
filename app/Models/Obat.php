<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
        'stok' 
    ];

    /**
     * Relasi ke detail_periksa
     * Digunakan untuk melihat histori pemakaian obat dalam resep
     */
    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }
}