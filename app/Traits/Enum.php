<?php

namespace App\Traits;

use App\Support\HttpCode;
use App\Support\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Enum
{
    /**
     * To mirror backed enums tryFrom - returns null on failed match.
     */
    public static function tryFromName(string $name): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }

    /**
     * To mirror backed enums from - throws ValueError on failed match.
     *
     * @throws HttpResponseException
     */
    public static function fromName(string $name): static
    {
        $case = self::tryFromName($name);
        if (! $case) {
            throw new HttpResponseException(
                Response::error($name.' is not a valid case for enum '.self::class, HttpCode::HTTP_FORBIDDEN)
            );
        }

        return $case;
    }
}
