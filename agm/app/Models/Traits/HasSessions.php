<?php

namespace App\Models\Traits;

use App\Models\Session;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSessions
{
    public function sessions(): MorphMany
    {
        return $this->morphMany(Session::class, 'sessionable');
    }
}
