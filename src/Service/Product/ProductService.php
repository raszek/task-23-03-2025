<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function create(ProductForm $form): Product
    {
        $product = new Product(
            name: $form->name,
            price: $form->price,
        );

        $this->entityManager->persist($product);

        $categories = $this->categoryRepository->findBy([
            'code' => $form->categories,
        ]);

        foreach ($categories as $category) {
            $product->addCategory($category);
        }

        $this->entityManager->flush();

        return $product;
    }
}
