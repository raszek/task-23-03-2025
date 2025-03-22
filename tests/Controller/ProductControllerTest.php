<?php

namespace App\Tests\Controller;

use App\Factory\CategoryFactory;
use App\Helper\JsonHelper;
use App\Repository\ProductRepository;

class ProductControllerTest extends WebTestCase
{

    /** @test */
    public function user_can_create_product()
    {
        $client = static::createClient();
        $client->followRedirects();

        CategoryFactory::createOne([
            'code' => 'tools'
        ]);

        $content = JsonHelper::encode([
            'name' => 'Hammer',
            'price' => '12.12',
            'categories' => [
                'tools',
            ]
        ]);

        $client->request(
            'POST',
            '/api/products',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $content,
        );

        $this->assertResponseStatusCodeSame(201);

        $response = JsonHelper::decode($client->getResponse()->getContent());

        $this->assertArrayHasKey('id', $response);
        $this->assertEquals('Hammer', $response['name']);
        $this->assertEquals('12.12', $response['price']);

        $createdProduct = $this->productRepository()->findOneBy([
            'name' => 'Hammer',
        ]);

        $this->assertNotNull($createdProduct);
        $this->assertEquals('12.12', $createdProduct->getPrice());

        $productCategory = $createdProduct->getCategories()->get(0);

        $this->assertNotNull($productCategory);
        $this->assertEquals('tools', $productCategory->getCode());
    }

    private function productRepository(): ProductRepository
    {
        return $this->getService(ProductRepository::class);
    }

}
