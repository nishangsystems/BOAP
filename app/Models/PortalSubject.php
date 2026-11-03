<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalSubject extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'portal_subjects';
    protected $fillable = ['name', 'certificate_id'];
}
