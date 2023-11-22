<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class UserCabang extends Authenticatable
{
    protected $table = 'user_cabangs';

    protected $fillable = [
        'username', 'password',
    ];

    // Sesuaikan atribut dan metode sesuai kebutuhan
}

