<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Question extends Model 
{   
    protected $connection = 'mongodb';
    
    protected $guarded = [];
    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answersQuestion() {
        return $this->hasMany(AnswerQuestion::class);
    }
}

