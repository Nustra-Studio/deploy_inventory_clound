<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;
    protected $table = 'members';
    public $incrementing = true;
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'kode_akses',
        'alamat',
        'status',
        'random_kode',
        'expait_kode',
    ];
}
