<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class UserCabang extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'user_cabangs';

    protected $fillable = [
        'username',
        'cabang_id',
        'uuid',
        'role',
        'api_key'
    ];
    protected $hidden = [
        'password',
    ];
    // Sesuaikan atribut dan metode sesuai kebutuhan
}

