<?php

namespace App\Transformers;

use App\Transformers\Fractal\CustomScopeFactory;
use App\Transformers\Fractal\Serializer\CustomArraySerializer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

trait CanTransform
{
    /**
     * @param LengthAwarePaginator|EloquentCollection|object|array $data
     * @param TransformerAbstract|string $transformer
     * @param null|string $resourceKey
     * @param array|string $includes
     * @param array|string $excludes
     * @return array
     */
    protected function transform($data, $transformer, $resourceKey = null, $includes = [], $excludes = [])
    {
        $request = app(Request::class);

        $transformer = $transformer instanceof TransformerAbstract ? $transformer : app($transformer);

        $fractal = (new Manager(app(CustomScopeFactory::class)))
            ->setSerializer(new CustomArraySerializer())
            ->parseIncludes($request->get('include') . ',' . (is_array($includes) ? implode(',', $includes) : $includes))
            ->parseExcludes($request->get('exclude') . ',' . (is_array($excludes) ? implode(',', $excludes) : $excludes));

        if ($data instanceof LengthAwarePaginator) {
            $result = new Collection($data->items(), $transformer);
            $result->setPaginator(new IlluminatePaginatorAdapter($data));
            $result->setMetaValue('total', $data->total());
        } elseif ($data instanceof EloquentCollection || is_array($data)) {
            $result = new Collection($data, $transformer);
            $result->setMetaValue('total', count($data));
        } else {
            $result = new Item($data, $transformer);
        }

        return $fractal->createData($result)->toArray();
    }
}
