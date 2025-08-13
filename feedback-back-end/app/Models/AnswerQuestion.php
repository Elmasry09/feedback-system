<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AnswerQuestion extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'answers_questions';

    protected $guarded = [];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
