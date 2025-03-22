<?php

namespace App\Event;

readonly class CreateProductEvent
{

    public function __construct(
        public int $id,
        public string $name,
        public string $price,
        public array $categories,
    ) {
    }

}
