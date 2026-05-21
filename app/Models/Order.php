<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['book_id', 'quantity', 'total'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
