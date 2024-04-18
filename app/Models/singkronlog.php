<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class singkronlog extends Model
{
    use HasFactory;
    protected $table = 'singkronlogs';
    protected $fillable = [
        'name',
        'keterangan',
        'status',
        'option'
    ];
}
