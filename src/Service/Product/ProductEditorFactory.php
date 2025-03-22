<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductEditorFactory
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function create(Product $product): ProductEditor
    {
        return new ProductEditor(
            product: $product,
            entityManager: $this->entityManager,
            categoryRepository: $this->categoryRepository,
        );
    }

}
