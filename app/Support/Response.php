<?php

namespace App\Support;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response as HttpResponse;

final class Response
{
    /**
     * Error Response
     * @param string|array $message
     * @param int $code
     * @return HttpResponse
     */
    public static function error(string|array $message, int $code = HttpCode::HTTP_UNPROCESSABLE_ENTITY): HttpResponse
    {
        return new HttpResponse([
            'statusCode' => $code,
            'error' => is_array($message) ? $message[0] : $message,
        ], $code);
    }

    /**
     * Success response
     * @param null $data
     * @param int $code
     * @return HttpResponse
     */
    public static function success($data = null, int $code = HttpCode::HTTP_OK): HttpResponse
    {
        return new HttpResponse([
            'statusCode' => $code,
            'error' => null,
            'data' => $data['data'] ?? $data,
        ], $code);
    }

    /**
     * Failed Validation
     * @param string $message
     * @param int $statusCode
     */
    public static function failedValidation(string $message, int $statusCode = HttpCode::HTTP_UNPROCESSABLE_ENTITY)
    {
        throw new HttpResponseException(
            self::error($message, $statusCode)
        );
    }
}
