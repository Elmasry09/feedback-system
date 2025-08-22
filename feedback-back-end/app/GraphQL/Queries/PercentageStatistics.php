<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Answer;

final readonly class PercentageStatistics
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {


        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        $lastMonthOrders = Order::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        if ($lastMonthOrders != 0) {
            $ordersPercentage = (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
        } else {
            $ordersPercentage = $currentMonthOrders > 0 ? 100 : 0;
        }

        $ordersStatus = "no change";
        if ($ordersPercentage > 0) {
            $ordersStatus = "increase";
        } elseif ($ordersPercentage < 0) {
            $ordersStatus = "decrease";
        }

        $currentMonthAnswers = Answer::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        $lastMonthAnswers = Answer::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        if ($lastMonthAnswers != 0) {
            $answersPercentage = (($currentMonthAnswers - $lastMonthAnswers) / $lastMonthAnswers) * 100;
        } else {
            $answersPercentage = $currentMonthAnswers > 0 ? 100 : 0;
        }

        $answersStatus = "no change";
        if ($answersPercentage > 0) {
            $answersStatus = "increase";
        } elseif ($answersPercentage < 0) {
            $answersStatus = "decrease";
        }

        return [
            'ordersPercentage' => [
                'percentage' => abs(round($ordersPercentage, 2)),
                'status' => $ordersStatus,
                'currentMonth' => $currentMonthOrders
            ],
            'answersPercentage' => [
                'percentage' => abs(round($answersPercentage, 2)),
                'status' => $answersStatus,
                'currentMonth' => $currentMonthAnswers
            ]
        ];
    }
    
}
