<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderImport extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'progress',
        'error_file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
