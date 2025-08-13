<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;

final readonly class DeleteOrder
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $order = Order::find($args['id']);

        if ($order->answers()->count() > 0) {
            $answers = $order->answers;
            foreach ($answers as $answer) {
                $answer->answersQuestions()->delete();
            }
        }

        $order->answers()->delete();

        $order->delete();

        return ['message' => 'Order deleted successfully'];
    }
}
