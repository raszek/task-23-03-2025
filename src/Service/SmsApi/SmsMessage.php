<?php

namespace App\Service\SmsApi;

readonly class SmsMessage
{

    public function __construct(
        public string $destinationPhone,
        public string $message
    ) {
    }

}
