<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Question;
use Illuminate\Support\Facades\Auth;

final readonly class UpdateQuestion
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $question = Question::find($args['id']);
        
        $user = Auth::user();
        $args['updated_by'] = $user->id;
        $question->update($args);

        return $question;
    }
}
