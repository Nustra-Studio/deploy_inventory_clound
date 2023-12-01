<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suplier extends Model
{
    use HasFactory;
    protected $table = 'supliers';
    protected $fillabel = [
        'nama',
        'product',
        'keterangan',
        'alamat',
        'status',
        'telepon',
        'category_barang_id',
        'uuid',
    ];
}
