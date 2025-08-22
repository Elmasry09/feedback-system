<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class DeleteUser
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::find($args['id']);
        $user->delete();

        return ['message' => 'User deleted successfully'];
    }
}
