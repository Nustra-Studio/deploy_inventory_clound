<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction_member extends Model
{
    use HasFactory;
    protected $table = 'transaction_members';
    protected $fillable = [
        'uuid',
        'nama_barang',
        'jumlah_barang',
        'harga',
        'id_member',
    ];
}
