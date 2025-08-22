<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class AnswerQuestion extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'answers_questions';

    protected $guarded = [];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer() : BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
