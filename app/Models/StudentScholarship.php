<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScholarship extends Model
{
    use HasFactory; protected $connection = "mysql2";
    protected $fillable = [
        'student_id',
        // 'scholarship_id',
        'batch_id',
        'amount',
        'user_id',
        'reason'
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
}
