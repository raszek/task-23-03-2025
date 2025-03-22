<?php

namespace App\Service\Product;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductEditor
{

    public function __construct(
        private Product $product,
        private EntityManagerInterface $entityManager,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function edit(ProductForm $form): void
    {
        $product = $this->product;

        $product->setName($form->name);
        $product->setPrice($form->price);
        
        $this->setCategories($form->categories);

        $this->entityManager->flush();
    }

    private function setCategories(array $categoriesToSet): void
    {
        $product = $this->product;

        $currentCategories = $product->getCategories()
            ->map(fn (Category $category) => $category->getCode())
            ->toArray();

        $categoriesToRemove = array_diff($currentCategories, $categoriesToSet);

        foreach ($product->getCategories() as $category) {
            if (in_array($category->getCode(), $categoriesToRemove)) {
                $product->removeCategory($category);
            }
        }

        $categoriesToCreate = array_diff($categoriesToSet, $currentCategories);

        $categoriesToCreateEntities = $this->categoryRepository->findBy(['code' => $categoriesToCreate]);

        foreach ($categoriesToCreateEntities as $categoryToCreateEntity) {
            $product->addCategory($categoryToCreateEntity);
        }
    }

}
