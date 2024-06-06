<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory; protected $connection = "mysql2";
    protected $fillable = ['name', 'value'];
    protected $table = 'colors';
}
