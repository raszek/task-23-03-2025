<?php

namespace App\Form;

use App\Validator\Categories;
use Symfony\Component\Validator\Constraints as Assert;

class ProductForm
{

    public function __construct(
        #[Assert\NotBlank()]
        public ?string $name = null,
        #[Assert\NotBlank()]
        #[Assert\GreaterThan(0)]
        #[Assert\Type('numeric')]
        public ?string $price = null,

        /**
         * @var string[]
         */
        #[Assert\NotBlank()]
        #[Categories]
        public array $categories = [],
    ) {
    }

}
