<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

final readonly class UpdateOrder
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $order = Order::find($args['id']);

        $user = Auth::user();
        $args['updated_by'] = $user->id;
        $order->update($args);

        return $order;
    }
}
