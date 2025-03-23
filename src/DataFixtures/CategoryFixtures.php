<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        foreach ($this->categoryCodes() as $categoryCode) {

            CategoryFactory::createOne([
                'code' => $categoryCode,
                'createdAt' => new DateTimeImmutable(),
                'updatedAt' => new DateTimeImmutable(),
            ]);
        }
    }

    /**
     * @return string[]
     */
    private function categoryCodes(): array
    {
        return [
            'tools',
            'house',
            'children',
            'sport',
            'company',
            'cars',
            'shopping'
        ];
    }
}
