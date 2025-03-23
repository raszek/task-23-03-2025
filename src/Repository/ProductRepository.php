<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function listProducts(): array
    {
        $queryBuilder = $this->createQueryBuilder('product');

        $queryBuilder
            ->addSelect('categories')
            ->join('product.categories', 'categories');

        return $queryBuilder->getQuery()->getResult();
    }
}
