<?php

namespace App\Helper;

class ArrayHelper
{

    public static function map(array $records, callable $callback): array
    {
        return array_map($callback, $records);
    }

    public static function filter(array $records, callable $callback): array
    {
        return array_values(array_filter($records, $callback));
    }

}
