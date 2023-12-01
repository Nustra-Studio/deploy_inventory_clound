<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_cabangs extends Model
{
    use HasFactory;
    protected $table = 'category_cabangs';
    protected $fillable = ['name','keterangan','id','status'];
}
