<?php

namespace App\Form;

use App\Entity\Product;
use App\Validator\Categories;
use App\Validator\UniqueForm;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueForm(
    entityClass: Product::class,
    fieldName: 'name',
    entityFieldName: 'name',
    formIdField: 'id'
)]
class ProductForm
{

    public function __construct(
        public ?int $id = null,
        #[Assert\NotBlank()]
        #[Assert\Length(max: 255)]
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
