<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Answer;

final readonly class DeleteAnswer
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $answer = Answer::find($args['id']);

        $answer->answersQuestions()->delete();
        $answer->delete();

        return ['message' => 'Answer deleted successfully'];
    }
}
