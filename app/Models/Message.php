<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory; protected $connection = "mysql2";
    protected $fillable = ['title', 'year_id', 'class_id', 'recipients', 'message', 'status', 'message_id'];

}
