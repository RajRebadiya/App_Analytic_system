<?php

namespace App\Repositories\Eloquent;

use App\Models\PushNotification;

class NotificationRepository extends BaseRepository
{
    public function __construct(PushNotification $model)
    {
        parent::__construct($model);
    }
}
