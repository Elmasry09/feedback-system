<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


final readonly class CreateQuestion
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = Auth::user();

        if ($args['type'] == 'choice') {
            $validator = Validator::make($args, [
                'choices' => 'required|array',
                'choices.*' => 'required|string'
            ])->validate();
        }

        $question = Question::create([
            'text' => $args['text'],
            'type' => $args['type'],
            'choices' => $validator['choices'] ?? null,
            'user_id' => $user->id
        ]);

        return $question;
    }
}
