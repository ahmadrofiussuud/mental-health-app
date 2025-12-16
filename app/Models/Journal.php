<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'mood',
        'is_anonymous'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
