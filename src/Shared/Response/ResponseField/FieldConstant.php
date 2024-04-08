<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

class FieldConstant
{
    public const PAYLOAD_FIELD = 'payload';

    public const ERROR_CODE_FIELD = 'error.code';
    public const ERROR_MESSAGE_FIELD = 'error.message';
    public const ERROR_META_FIELD = 'error.meta';

    public const META_RESOURCE_FIELD = 'meta.resource';
    public const META_OFFSET_FIELD = 'meta.offset';
    public const META_LIMIT_FIELD = 'meta.limit';
    public const META_TOTAL_FIELD = 'meta.total';
}
