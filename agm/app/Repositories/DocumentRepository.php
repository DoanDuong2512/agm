<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\CMS\App\Models\Document;

class DocumentRepository extends BaseRepository
{
    public function model()
    {
        return Document::class;
    }
}