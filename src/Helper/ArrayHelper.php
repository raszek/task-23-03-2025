<?php

namespace App\Helper;

class ArrayHelper
{

    public static function map(array $records, callable $callback): array
    {
        return array_map($callback, $records);
    }

}
