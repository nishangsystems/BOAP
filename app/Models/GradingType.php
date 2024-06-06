<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingType extends Model
{
    use HasFactory; protected $connection = "mysql2";

    protected $table = 'grading_types';

    protected $fillable = ['name'];

    public function grading()
    {
        # code...
        return $this->hasMany(Grading::class, 'grading_type_id');
    }
}
