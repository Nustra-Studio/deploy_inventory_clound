<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_cabang extends Model
{
    use HasFactory;
    protected $table = 'user_cabangs';
    protected $fillable = [
        'cabang_id',
        'uuid',
        'username',
        'password',
        'role',
        'api_key',
        'created_at',
        'updated_at'
    ];

}
