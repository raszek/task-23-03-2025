<?php

namespace App\Tests\Controller;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
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

    /** @test */
    public function user_can_edit_product()
    {
        $client = static::createClient();
        $client->followRedirects();

        $houseCategory = CategoryFactory::createOne([
            'code' => 'house'
        ]);

        CategoryFactory::createOne([
            'code' => 'tools'
        ]);

        $product = ProductFactory::createOne([
            'name' => 'Toothbrush',
            'price' => '2.2',
            'categories' => [$houseCategory]
        ]);

        $content = JsonHelper::encode([
            'name' => 'Hammer',
            'price' => '12.12',
            'categories' => [
                'tools',
                'house'
            ]
        ]);

        $client->request(
            'PUT',
            '/api/products/' . $product->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $content,
        );

        $this->assertResponseStatusCodeSame(200);

        $response = JsonHelper::decode($client->getResponse()->getContent());

        $this->assertArrayHasKey('id', $response);
        $this->assertEquals('Hammer', $response['name']);
        $this->assertEquals('12.12', $response['price']);

        $updatedProduct = $this->productRepository()->findOneBy([
            'id' => $product->getId(),
        ]);

        $this->assertNotNull($updatedProduct);
        $this->assertEquals('12.12', $updatedProduct->getPrice());

        $this->assertCount(2, $updatedProduct->getCategories());
    }

    /** @test */
    public function user_can_list_products()
    {
        $client = static::createClient();
        $client->followRedirects();

        $toolCategory = CategoryFactory::createOne([
            'code' => 'tools'
        ]);

        $houseCategory = CategoryFactory::createOne([
            'code' => 'house'
        ]);

        ProductFactory::createOne([
            'name' => 'Hammer',
            'price' => '12.12',
            'categories' => [$toolCategory, $houseCategory]
        ]);

        ProductFactory::createOne([
            'name' => 'Fork',
            'price' => '2.2',
            'categories' => [$houseCategory]
        ]);

        $client->request(
            'GET',
            '/api/products',
            server: ['ACCEPT' => 'application/json'],
        );

        $this->assertResponseIsSuccessful();

        $response = JsonHelper::decode($client->getResponse()->getContent());

        $this->assertCount(2, $response);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertEquals('Hammer', $response[0]['name']);
        $this->assertEquals('12.12', $response[0]['price']);
        $this->assertEquals(['tools', 'house'], $response[0]['categories']);

        $this->assertArrayHasKey('id', $response[1]);
        $this->assertEquals('Fork', $response[1]['name']);
        $this->assertEquals('2.2', $response[1]['price']);
        $this->assertEquals(['house'], $response[1]['categories']);
    }

    /** @test */
    public function user_can_view_product()
    {
        $client = static::createClient();
        $client->followRedirects();

        $toolCategory = CategoryFactory::createOne([
            'code' => 'tools'
        ]);

        $houseCategory = CategoryFactory::createOne([
            'code' => 'house'
        ]);

        $product = ProductFactory::createOne([
            'name' => 'Hammer',
            'price' => '12.12',
            'categories' => [$toolCategory, $houseCategory]
        ]);

        $client->request(
            'GET',
            '/api/products/' . $product->getId(),
            server: ['ACCEPT' => 'application/json'],
        );

        $this->assertResponseIsSuccessful();

        $response = JsonHelper::decode($client->getResponse()->getContent());

        $this->assertArrayHasKey('id', $response);
        $this->assertEquals('Hammer', $response['name']);
        $this->assertEquals('12.12', $response['price']);
        $this->assertEquals(['tools', 'house'], $response['categories']);
    }

    /** @test */
    public function user_can_remove_product()
    {
        $client = static::createClient();
        $client->followRedirects();

        $product = ProductFactory::createOne([
            'name' => 'Hammer',
            'price' => '12.12',
            'categories' => []
        ]);

        $client->request(
            'DELETE',
            '/api/products/' . $product->getId(),
        );

        $this->assertResponseStatusCodeSame(204);

        $removedProduct = $this->productRepository()->findOneBy([
            'id' => $product->getId()
        ]);

        $this->assertNull($removedProduct);
    }

    /** @test */
    public function user_cannot_create_product_if_category_does_not_exist()
    {
        $client = static::createClient();
        $client->followRedirects();

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

        $this->assertResponseStatusCodeSame(422);
    }

    /** @test */
    public function user_can_edit_product_price()
    {
        $client = static::createClient();
        $client->followRedirects();

        $houseCategory = CategoryFactory::createOne([
            'code' => 'house'
        ]);

        $product = ProductFactory::createOne([
            'name' => 'Toothbrush',
            'price' => '2.2',
            'categories' => [$houseCategory]
        ]);

        $content = JsonHelper::encode([
            'name' => 'Toothbrush',
            'price' => '4.4',
            'categories' => [
                'house'
            ]
        ]);

        $client->request(
            'PUT',
            '/api/products/' . $product->getId(),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $content,
        );

        $this->assertResponseStatusCodeSame(200);

        $response = JsonHelper::decode($client->getResponse()->getContent());

        $this->assertArrayHasKey('id', $response);
        $this->assertEquals('Toothbrush', $response['name']);
        $this->assertEquals('4.4', $response['price']);

        $updatedProduct = $this->productRepository()->findOneBy([
            'id' => $product->getId(),
        ]);

        $this->assertNotNull($updatedProduct);
        $this->assertEquals('4.40', $updatedProduct->getPrice());
    }

    private function productRepository(): ProductRepository
    {
        return $this->getService(ProductRepository::class);
    }
}
