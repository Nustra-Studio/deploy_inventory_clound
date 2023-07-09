<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class harga_khusus extends Model
{
    use HasFactory;
    protected $table = 'harga_khusus';
    protected $fillable = [
        'harga',
        'jumlah_minimal',
        'diskon',
        'keterangan',
        'satuan',
        'uuid',
        'id_barang',
    ];
}
