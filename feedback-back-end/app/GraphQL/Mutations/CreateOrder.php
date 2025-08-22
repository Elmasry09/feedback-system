<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

final readonly class CreateOrder
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, [
            'phone' => 'phone:EG|required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:6048',
            'name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255'
        ] , [
            'phone.phone' => 'The phone number must be a valid Egyptian phone number.',
        ])->validate();

        if (isset($args['image'])) {
            $args['image'] = ImageService::uploadImage($args['image'], 'orders');
        }

        $user = Auth::user();
        $order = Order::create([
            'name' => $args['name'],
            'phone' => $args['phone'],
            'image' => $args['image'],
            'product_name' => $args['product_name'],
            'user_id' => $user->id
        ]);

        return $order;
    }
}
