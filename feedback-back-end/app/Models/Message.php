<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Message extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
