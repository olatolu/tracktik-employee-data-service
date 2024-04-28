<?php

namespace App\Traits;

use App\Enums\HttpMethodsEnum;
use App\Support\HttpCode;
use App\Support\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

trait HttpClient
{
    /**
     * API call to other services
     *
     * @throws HttpResponseException
     */
    public static function makeRequest(HttpMethodsEnum $method, string $url, array $body = null, array $headers = null, bool $throw = false, int $timeout = 5): array
    {
        // Instantiate HTTP
        $http = Http::withHeaders($headers ?? [])
            ->connectTimeout($timeout)
            ->timeout($timeout + (int) config('app.request_timeout'))
            ->retry((int) config('app.request_retries'), (int) config('app.request_retries_sleep'));
        // Method check
        if ($method == HttpMethodsEnum::GET) {
            $http->withQueryParameters($body ?? []);
        } elseif ( $method != HttpMethodsEnum::GET && !empty($body)) {
            $http->withBody(json_encode($body));
        }
        try {
            // make api call

            $response = $http->send((string) $method->value, $url);
            // check for successful response
            if ($response->successful()) {
                return $response->json();
            }
            $error = json_decode($response->body(), true);
            throw new HttpResponseException(
                Response::error($error['error'] ?? 'Unsuccessful request', $error['statusCode'] ?? HttpCode::HTTP_BAD_REQUEST)
            );
        } catch (Throwable $th) {
            logger()->error($url, [$th->getMessage(), $th->getCode()]);
            if ($throw) {
                $response = json_decode(Str::after($th->getMessage(), ':') ?? '');
                throw new HttpResponseException(
                    Response::error($response->error ?? 'Unsuccessful request', self::getHttpStatusCode($th, $response))
                );
            }

            return [];
        }
        $error = json_decode($response->body(), true);
        throw new HttpResponseException(
            Response::error($error['error'] ?? 'Unsuccessful request', $error['statusCode'] ?? HttpCode::HTTP_NOT_ACCEPTABLE)
        );
    }

    /**
     * Get Response Http Status Code
     */
    private static function getHttpStatusCode(Throwable $th, mixed $response = null): int
    {
        // Check for response statusCode
        if (! empty($response->statusCode)) {
            return $response->statusCode;
        }
        // Check for throwable error code
        if (! empty($th->getCode())) {
            return $th->getCode();
        }

        return HttpCode::HTTP_NOT_ACCEPTABLE;
    }
}
