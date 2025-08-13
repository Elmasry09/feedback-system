<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Models\Answer;
use App\Models\AnswerQuestion;
use Illuminate\Support\Facades\Validator;

final readonly class CreateAnswer
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, [
            'phone' => 'phone:EG|required',
            'answersQuestions' => 'required|array',
            'answersQuestions.*.question_id' => 'required|exists:questions,id',
            'answersQuestions.*.answer' => 'required|string',
        ], [
            'phone.phone' => 'The phone number must be a valid Egyptian phone number.',
        ])->validate();

        $data = $args["answersQuestions"];
        $order = Order::where('phone', $args['phone'])
            ->latest('created_at')
            ->first();

        $answer = Answer::create([
            'order_id' => $order->id
        ]);

        foreach ($data as $answerQuestion) {
            $item = AnswerQuestion::create([
                'answer_id' => $answer->id,
                'text_answer' => $answerQuestion['answer'],
                'question_id' => $answerQuestion['question_id']
            ]);
        }

        return $answer;
    }
}
