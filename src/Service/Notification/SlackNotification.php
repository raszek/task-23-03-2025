<?php

namespace App\Service\Notification;

use App\Service\SlackApi\SlackApi;
use App\Service\SlackApi\SlackMessage;

readonly class SlackNotification implements Notification
{

    public function __construct(
        private SlackApi $slackApi,
        private SlackMessage $slackMessage,
    ) {
    }


    public function send(): void
    {
        $this->slackApi->send($this->slackMessage);
    }
}
