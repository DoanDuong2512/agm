<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\CMS\App\Models\Document;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class DocumentCriteriaRequest implements CriteriaInterface
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('is_published', Document::PUBLISHED);
        $title = $this->request->get('title');
        $orderBy = $this->request->get('order_by', 'created_at');
        $orderType = $this->request->get('order_type', 'desc');
        if (!empty($title)) {
            $model = $model->where('title', 'like', '%' . $title . '%');
        }
        $model = $model->when($this->request->has('title'), function (Builder $query) {
            return $query->where('title', 'like', '%' . $this->request->title . '%');
        });
        $model = $model->orderBy($orderBy, $orderType);
        return $model;
    }
}
