<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Order;

final readonly class OrdersStatistics
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $ordersHasAnswer = Order::whereHas('answer')->count();
        $ordersDosentHaveAnswer = Order::doesntHave('answer')->count();

        return [
            'ordersHasAnswer' => $ordersHasAnswer,
            'ordersDosentHaveAnswer' => $ordersDosentHaveAnswer
        ];
    }
}
