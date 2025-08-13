<?php

namespace App\GraphQL\Resolvers;

use App\Models\Customer;

class CustomerResolver
{
    public function customer($root, array $args)
    {
        return Customer::find($root['client_id']);
    }
}
