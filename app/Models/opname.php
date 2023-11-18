<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class opname extends Model
{
    use HasFactory;
    protected $table = 'opnames';
    protected $fillable = [
        'uuid',
        'barcode',
        'stock',
        'perubahan',
        'id_toko',
        'status',
        'keterangan'
    ];
}
