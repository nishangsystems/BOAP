<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory; protected $connection = "mysql2";

    protected $fillable = ['student_id', 'amount', 'year_id', 'tel', 'status','purpose','payment_method','reference', 'transaction_id', 'payment_id', 'financialTransactionId', 'semester_id'];

    protected $table = 'transactions';
}
