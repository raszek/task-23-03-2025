<?php

namespace App\Service\SmsApi;

interface SmsApi
{

    public function send(SmsMessage $message): void;
}
