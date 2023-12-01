<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_barang extends Model
{
    use HasFactory;
    protected $table = 'category_barangs';
    protected $fillable = ['name','id','uuid','keterangan'];
    protected $guarded = ['uuid'];
}
