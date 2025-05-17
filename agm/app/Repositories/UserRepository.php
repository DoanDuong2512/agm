<?php

namespace App\Repositories;

use App\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepository
 * @package Modules\Platform\User\Repositories
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return User::class;
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
