<?php

namespace App\Service\SlackApi;

readonly class SlackMessage
{

    public function __construct(
        public string $destination,
        public string $message
    ) {
    }

}
