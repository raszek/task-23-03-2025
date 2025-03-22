<?php

namespace App\Service\Notification;

use App\Service\SmsApi\SmsApi;
use App\Service\SmsApi\SmsMessage;

readonly class SmsNotification implements Notification
{

    public function __construct(
        private SmsApi $smsApi,
        private SmsMessage $smsMessage,
    ) {
    }

    public function send(): void
    {
        $this->smsApi->send($this->smsMessage);
    }
}
