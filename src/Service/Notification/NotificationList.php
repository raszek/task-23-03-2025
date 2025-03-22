<?php

namespace App\Service\Notification;

class NotificationList
{

    /**
     * @return string[]
     */
    public function get(): array
    {
        return [
            SlackNotification::class,
            SmsNotification::class,
            EmailNotification::class,
        ];
    }
}
