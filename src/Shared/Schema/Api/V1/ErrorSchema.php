<?php

declare(strict_types=1);

namespace App\Shared\Schema\Api\V1;

use App\Shared\Response\ErrorCode;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\AdditionalProperties;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    required: [
        ErrorSchema::CODE_FIELD,
        ErrorSchema::MESSAGE_FIELD,
    ],
    properties: [
        new Property(property: ErrorSchema::CODE_FIELD, ref: new Model(type: ErrorCode::class)),
        new Property(property: ErrorSchema::MESSAGE_FIELD, type: 'string'),
        new Property(property: ErrorSchema::META_FIELD, type: 'object', additionalProperties: new AdditionalProperties(type: 'string')),
    ]
)]
class ErrorSchema
{
    public const CODE_FIELD = 'code';
    public const MESSAGE_FIELD = 'message';
    public const META_FIELD = 'meta';
}
