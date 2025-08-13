<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class CreateUser
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => bcrypt($args['password']),
        ]);

        return $user;
    }
}
