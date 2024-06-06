<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectNotes extends Model
{
    use HasFactory; protected $connection = "mysql2";
    protected $fillable = [
        'class_subject_id',
        'note_name',
        'note_path',
        'status',
        'type'
    ];

    /**
     * relationshipe between class_subject and notes
     */
    public function class_subject()
    {
        return $this->belongsTo(Subjects::class);
    }
}
