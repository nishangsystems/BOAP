<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramAdmin extends Model
{
    use HasFactory;
    protected $table = 'program_admins';
    protected $fillable = ['chancellor', 'pro_chancellor', 'vice_chancellor', 'dvc_academic', 'dvc_admin', 'dvc_coop', 'registrar', 'program_id', 'promoter'];
    protected $connection = 'mysql2';
}
