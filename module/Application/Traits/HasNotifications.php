<?php

namespace Module\Application\Traits;

use Module\Application\Notification;

trait HasNotifications
{
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifyable');
    }
}
