<?php

namespace App\Repositories\Eloquent;

use App\Models\Advertisement;

class AdvertisementRepository extends BaseRepository
{
    public function __construct(Advertisement $model)
    {
        parent::__construct($model);
    }
}
