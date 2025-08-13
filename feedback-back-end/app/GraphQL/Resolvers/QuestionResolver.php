<?php

namespace App\GraphQL\Resolvers;

use App\Models\User;

class QuestionResolver
{
    public function user($root, array $args)
    {
        return User::find($root['user_id']);
    }
}
