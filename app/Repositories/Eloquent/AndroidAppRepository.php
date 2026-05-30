<?php

namespace App\Repositories\Eloquent;

use App\Models\AndroidApp;

class AndroidAppRepository extends BaseRepository
{
    public function __construct(AndroidApp $model)
    {
        parent::__construct($model);
    }
}
