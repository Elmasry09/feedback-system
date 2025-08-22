<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class UpdateUser
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::find($args['id']);

        if (isset($args['password'])) {
            $args['password'] = bcrypt($args['password']);
        }

        $user->update($args);
        return $user;
    }
}
