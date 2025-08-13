<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

final readonly class CreateOrder
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $validator = Validator::make($args, [
            'phone' => 'phone:EG|required', 
        ] , [
            'phone.phone' => 'The phone number must be a valid Egyptian phone number.',
        ])->validate();
        
       
        $user = Auth::user();
        
        $order = Order::create([
            'name' => $args['name'],
            'phone' => $args['phone'],
            'user_id' => $user->id,
        ]);

        return $order;
    }
}
