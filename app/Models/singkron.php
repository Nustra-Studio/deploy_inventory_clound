<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class singkron extends Model
{
    use HasFactory;
    protected $table = 'singkrons';
    protected $fillable = [
        'uuid',
        'name',
        'keterangan',
        'status',
        'option'
    ];
}
