<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelBase extends Model
{
    public static function autoMeta()
    {
        static::creating(function ($model){
            if (auth()->user()) {
                $model->created_by = auth()->user()->id ?? null;
                $model->updated_by = $model->created_by;
            }
        });
        static::updating(function ($model){
            if (auth()->user()) {
                $model->updated_by = auth()->user()->id ?? null;
            }
        });
        static::deleting(function ($model){
            if (auth()->user()) {
                $model->updated_by = auth()->user()->id ?? null;
                $model->save(); // it runs updating event
            }
        });
    }
}
