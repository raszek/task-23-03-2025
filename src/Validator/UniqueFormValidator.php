<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniqueFormValidator extends ConstraintValidator
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        $fieldName = $constraint->fieldName;
        $fieldId = $constraint->formIdField;
        $entityFieldName = $constraint->entityFieldName;

        $fieldValue = $value->$fieldName;

        $repository = $this->entityManager->getRepository($constraint->entityClass);

        $foundRecord = $repository->findOneBy([
            $entityFieldName => $fieldValue
        ]);

        if (!$foundRecord) {
            return;
        }

        $fieldIdValue = $value->$fieldId;

        if (!$fieldIdValue || $fieldIdValue !== $foundRecord->getId()) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath($fieldName)
                ->addViolation();
        }
    }
}
