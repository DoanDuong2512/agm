<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;
use Illuminate\Http\Request;

class UserCriteriaRequest implements CriteriaInterface
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $column = $this->request->input('order_by', 'created_at');
        $order_type = $this->request->input('order_type', 'DESC');
        $model = $model->orderBy($column, $order_type);
        if ($this->request->get('keywords')) {
            $keywords = $this->request->get('keywords');
            $model = $model->where(function (Builder $query) use ($keywords) {
                $query->where('username', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('email', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $keywords . '%');
            });
        }
        return $model;
    }
}

