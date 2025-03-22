<?php

namespace App\Controller;

use App\Form\ProductForm;
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
    ) {
    }


    #[Route('/products', name: 'app_create_product', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ProductForm $form,
    ): Response
    {
        $createdProduct = $this->productService->create($form);

        return new JsonResponse($createdProduct->toArray(), status: 201);
    }

}
