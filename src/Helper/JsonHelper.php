<?php

namespace App\Helper;

class JsonHelper
{

    public static function encode(mixed $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public static function decode(string $json): mixed
    {
        return json_decode($json, true, flags: JSON_THROW_ON_ERROR);
    }

}
