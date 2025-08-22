<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Services\ImageService;

final readonly class UpdateOrder
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $order = Order::find($args['id']);
        if (isset($args['image'])) {
            $args['image'] = ImageService::uploadImage($args['image'], 'orders');
            ImageService::deleteImage($order->image);
        }

        $order->update($args);
        return $order;
    }
}
