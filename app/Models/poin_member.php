<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class poin_member extends Model
{
    use HasFactory;
    protected $table = 'poin_members';
    protected $fillable = [
        'uuid',
        'id_member',
        'poin',
        'status',
        'expaid'
    ];
}
