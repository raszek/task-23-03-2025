<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Event\CreateProductEvent;
use App\Form\ProductForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ProductService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CategoryRepository $categoryRepository,
        private MessageBusInterface $messageBus,
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

        $this->messageBus->dispatch(new CreateProductEvent(
            id: $product->getId(),
            name: $form->name,
            price: $form->price,
            categories: $form->categories,
        ));

        return $product;
    }

    public function remove(Product $product): void
    {
        $this->entityManager->remove($product);

        $this->entityManager->flush();
    }
}
