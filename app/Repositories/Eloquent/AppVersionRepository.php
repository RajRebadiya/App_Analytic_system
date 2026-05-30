<?php

namespace App\Repositories\Eloquent;

use App\Models\AppVersion;

class AppVersionRepository extends BaseRepository
{
    public function __construct(AppVersion $model)
    {
        parent::__construct($model);
    }
}
