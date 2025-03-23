<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Helper\ArrayHelper;
use App\Repository\ProductRepository;
use App\Service\Product\ProductEditorFactory;
use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ProductController extends AbstractController
{

    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductRepository $productRepository,
    ) {
    }


    #[Route('/products', name: 'app_list_products', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productRepository->listProducts();

        $records = ArrayHelper::map($products, fn(Product $product) => $product->toArray());

        return new JsonResponse($records);
    }

    #[Route('/products/{id}', name: 'app_view_product', methods: ['GET'])]
    public function view(Product $product): JsonResponse
    {
        return new JsonResponse($product->toArray());
    }

    #[Route('/products', name: 'app_create_product', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ProductForm $form,
    ): JsonResponse
    {
        $createdProduct = $this->productService->create($form);

        return new JsonResponse($createdProduct->toArray(), status: 201);
    }

    #[Route('/products/{id}', name: 'app_update_product', methods: ['PUT'])]
    public function update(
        Product $product,
        #[MapRequestPayload] ProductForm $form,
        ProductEditorFactory $factory
    ): JsonResponse
    {
        $productEditor = $factory->create($product);

        $productEditor->edit($form);

        return new JsonResponse($product->toArray());
    }

    #[Route('/products/{id}', name: 'app_remove_product', methods: ['DELETE'])]
    public function remove(Product $product): Response
    {
        $this->productService->remove($product);

        return new Response(status: 204);
    }

}
