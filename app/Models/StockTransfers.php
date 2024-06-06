<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfers extends Model
{
    use HasFactory; protected $connection = "mysql2";

    protected $fillable = ['sender_campus', 'stock_id', 'receiver_campus', 'user_id', 'type', 'quantity'];
    protected $table = 'stock_transfers';

    public function stock()
    {
        # code...
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function campus()
    {
        # code...
        $campus_id = $this->sender_campus == null ? $this->receiver_campus : $this->sender_campus;
        return Campus::find($campus_id);
    }
}
