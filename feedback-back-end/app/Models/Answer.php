<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Answer extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function answersQuestions()
    {
        return $this->hasMany(AnswerQuestion::class);
    }

}
