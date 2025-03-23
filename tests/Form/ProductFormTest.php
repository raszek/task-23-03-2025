<?php

namespace App\Tests\Form;

use App\Form\ProductForm;
use App\Tests\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductFormTest extends KernelTestCase
{

    /** @test */
    public function product_form_must_have_at_least_one_category()
    {
        $form = new ProductForm(
            name: 'Toothbrush',
            price: '12.12',
            categories: []
        );

        $errors = $this->validator()->validate($form);
        
        $this->assertCount(1, $errors);

        $error = $errors->get(0);

        $this->assertEquals('categories', $error->getPropertyPath());
        $this->assertEquals('This value should not be blank.', $error->getMessage());
    }

    /** @test */
    public function category_must_be_in_database()
    {
        $form = new ProductForm(
            name: 'Toothbrush',
            price: '12.12',
            categories: [
                'tools'
            ]
        );

        $errors = $this->validator()->validate($form);

        $this->assertCount(1, $errors);

        $error = $errors->get(0);

        $this->assertEquals('categories', $error->getPropertyPath());
        $this->assertEquals('Invalid category. Categories [tools] does not exist in database.', $error->getMessage());
    }

    private function validator(): ValidatorInterface
    {
        return $this->getService(ValidatorInterface::class);
    }
}
