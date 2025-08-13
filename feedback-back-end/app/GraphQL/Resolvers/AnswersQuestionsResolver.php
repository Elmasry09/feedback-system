<?php

namespace App\GraphQL\Resolvers;

use App\Models\AnswersQuestions;


class AnswersQuestionsResolver
{
    public function answersQuestions($root, array $args)
    {
        $answersQuestions = AnswersQuestions::where('answer_id' , $root['id'])->get();
        return $answersQuestions;
    }
}
