<?php

namespace App\DataFixtures;

use App\Factory\ProductFactory;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->productList() as $product) {
            ProductFactory::createOne([
                'name' => $product['name'],
                'price' => $product['price'],
                'categories' => $this->categoryRepository->findBy([
                    'code' => $product['categories']
                ])
            ]);
        }
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }

    private function productList(): array
    {
        return [
            [
                'name' => 'Toothbrush',
                'price' => '4.4',
                'categories' => ['house', 'children']
            ],
            [
                'name' => 'BMW',
                'price' => '100000.12',
                'categories' => ['cars']
            ],
            [
                'name' => 'Hammer',
                'price' => '12.12',
                'categories' => ['tools'],
            ],
            [
                'name' => 'Ball',
                'price' => '8.8',
                'categories' => ['sport'],
            ],
        ];
    }
}
