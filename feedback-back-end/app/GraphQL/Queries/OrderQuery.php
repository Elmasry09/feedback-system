<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Order;
use GraphQL\Error\Error;

final readonly class OrderQuery
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $order = Order::where('slug', $args['slug'])->first();

        if (! $order) {
            throw new Error('Order not found.');
        }

        if ($order->answer()->exists()) {
            throw new Error('This order already has an answer.');
        }

        return $order;
    }
}
