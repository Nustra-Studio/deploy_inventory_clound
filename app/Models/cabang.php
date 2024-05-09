<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cabang extends Model
{
    use HasFactory;
    protected $table = 'cabangs';
    protected $fillable = [
        'nama',
        'kepala_cabang',
        'telepon',
        'alamat',
        'category_id',
        'keterangan',
        'database',
        'uuid',
        'keterangan'
    ];
}
