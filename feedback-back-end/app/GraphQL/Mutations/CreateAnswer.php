<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Models\Answer;
use GraphQL\Error\Error;
use App\Models\AnswerQuestion;
use Illuminate\Support\Facades\Validator;

final readonly class CreateAnswer
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, [
            'phone' => 'phone:EG',
            'order_id' => 'required|exists:orders,id',
            'answersQuestions' => 'required|array',
            'answersQuestions.*.question_id' => 'required|exists:questions,id',
            'answersQuestions.*.answer' => 'required|string',
            'answersQuestions.*.type' => 'required|string|in:text,choice,rating',
        ], [
            'phone.phone' => 'The phone number must be a valid Egyptian phone number.',
        ])->validate();

        $data = $args["answersQuestions"];
        $order = Order::find($args['order_id']);
        if ($order->answer()->exists()) {
            throw new Error('This order already has an answer.');
        }

        $answer = Answer::create([
            'order_id' => $args['order_id'],
            'phone' => $args['phone'],
        ]);

        foreach ($data as $answerQuestion) {
            AnswerQuestion::create([
                'answer_id' => $answer->id,
                'text_answer' => $answerQuestion['answer'],
                'type' => $answerQuestion['type'],
                'question_id' => $answerQuestion['question_id']
            ]);
        }

        return $answer;
    }
}
