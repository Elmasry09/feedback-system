<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Question;
use Illuminate\Support\Facades\Auth;


final readonly class CreateQuestion
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = Auth::user();
        
        $question = Question::create([
            'text' => $args['text'],
            'user_id' => $user->id
        ]);

        return $question;
    }
}
