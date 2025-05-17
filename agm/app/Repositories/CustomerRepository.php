<?php

namespace App\Repositories;

use App\Models\Customer;
use Prettus\Repository\Eloquent\BaseRepository;

class CustomerRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Customer::class;
    }

    public function create(array $attributes)
    {
        return parent::create($attributes);
    }


    public function update(array $attributes, $id)
    {
        return parent::update($attributes, $id);
    }

}
