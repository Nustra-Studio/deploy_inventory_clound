<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class history_transaction extends Model
{
    use HasFactory;
    protected $table = 'history_transactions';
    protected $fillable = [
        'uuid',
        'name',
        'jumlah',
        'kode_barang',
        'uuid_barang',
        'harga_pokok',
        'harga_jual',
        'id_supllayer',
        'id_cabang',
        'keterangan',
        'status',

    ];
}
