<?php

namespace App\Support;

use Illuminate\Http\Exceptions\HttpResponseException;

trait GeneralException
{
    /**
     * Build HttpResponseException response exception
     *
     *
     * @throws HttpResponseException
     */
    public static function exception(string $message, int $status = HttpCode::HTTP_UNPROCESSABLE_ENTITY): void
    {
        throw new HttpResponseException(
            Response::error($message, $status)
        );
    }
}
