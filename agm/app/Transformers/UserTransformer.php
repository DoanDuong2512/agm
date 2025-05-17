<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
class UserTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
    ];


    public function transform(User $user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'address' => $user->address,
            'phone' => $user->phone,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

//
//    public function includeCustomer(OSTag $osTag)
//    {
//        return $osTag->customers() ? $this->item($osTag->customers(), new CustomerInfoTransformer())
//            : $this->primitive(null);
//    }
}
