<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Order;

final readonly class MonthlyOrders
{
    public function __invoke(null $_, array $args)
    {
        $orders = Order::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => [
                            'month' => ['$month' => '$created_at']
                        ],
                        'total' => ['$sum' => 1]
                    ]
                ],
                [
                    '$sort' => ['_id.month' => 1]
                ]
            ]);
        });

        return collect($orders)->map(function ($item) {
            $monthNum = $item->_id['month'];
            $monthName = \Carbon\Carbon::create()->month($monthNum)->format('M');
            return [
                'month' => $monthName,
                'total' => $item->total
            ];
        });
    }
}
