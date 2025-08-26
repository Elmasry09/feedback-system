<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Models\Answer;
use App\Models\Message;
use App\Services\ImageService;

final readonly class DeleteOrder
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $order = Order::find($args['id']);

        $answer = Answer::where('order_id', $args['id'])->first();
        $massage = Message::where('order_id', $args['id'])->first();
        if ($answer) {
            $answer->answersQuestions()->delete();
            $order->answer()->delete();
        }
        if ($massage) {
            $order->message()->delete();
        }
        
        ImageService::deleteImage($order->image);
        $order->delete();

        return ['message' => 'Order deleted successfully'];
    }
}
