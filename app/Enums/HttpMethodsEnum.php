<?php

namespace App\Enums;

use App\Traits\Enum;

/**
 * HttpMethodsEnum
 */

enum HttpMethodsEnum: string
{
    use Enum;
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
}
