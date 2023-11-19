<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class UserCabang extends Authenticatable
{
    protected $table = 'user_cabangs';

    protected $fillable = [
        'username', 'password',
    ];

    // Sesuaikan atribut dan metode sesuai kebutuhan
}

