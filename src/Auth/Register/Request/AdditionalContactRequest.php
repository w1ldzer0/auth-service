<?php

declare(strict_types=1);

namespace App\Auth\Register\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdditionalContactRequest
{
    public function __construct(
        #[NotBlank]
        #[Length(max: 50)]
        private readonly string $type,
        #[NotBlank]
        #[Length(max: 100)]
        private readonly string $contact
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getContact(): string
    {
        return $this->contact;
    }
}
