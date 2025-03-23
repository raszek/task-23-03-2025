<?php

namespace App\Validator;

use App\Entity\Category;
use App\Helper\ArrayHelper;
use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class CategoriesValidator extends ConstraintValidator
{
    
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var Categories $constraint */
        if (!is_array($value) || empty($value)) {
            return;
        }

        $foundCategories = $this->categoryRepository->findBy([
            'code' => $value,
        ]);

        if (count($foundCategories) !== count($value)) {
            $existingCodes = ArrayHelper::map(
                $foundCategories,
                fn(Category $category) => $category->getCode()
            );

            $notExistingCodes = array_diff($value, $existingCodes);

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', implode(',', $notExistingCodes))
                ->addViolation()
            ;
        }

    }
}
