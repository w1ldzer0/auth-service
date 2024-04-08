<?php

declare(strict_types=1);

namespace App\Shared;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestViolationListConverter
{
    public function convertToArray(ConstraintViolationListInterface $list): array
    {
        $errors = [];
        /** @var ConstraintViolation $violation */
        foreach ($list as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}
