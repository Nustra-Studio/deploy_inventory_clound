<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    use HasFactory;
    
    protected $table = 'barangs';
    protected $fillable = [
        'name',
        'merek_barang',
        'uuid',
        'id_supplier',
        'category_id',
        'harga',
        'harga_jual',
        'stok',
        'keterangan',
        'harga_pokok',
        'harga_grosir',
        'kode_barang',
    ];
}
