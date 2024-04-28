<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\ApiToken;
use App\Support\Constant;
use App\Support\HttpCode;
use App\Support\Response;
use App\Traits\HttpClient;
use App\Enums\HttpMethodsEnum;
use App\Support\GeneralException;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class Service
{
    use HttpClient, GeneralException;


    /**
     * get service api token from tracktik
     * @return string
     */
    public static function get_token(): string
    {

        if ($token = ApiToken::latest()->first()) {

            return ($token->created_at > Carbon::now()->subSeconds((int)$token->expires_in - 60)) ?
                $token->token
                : self::fetch_api_token();
        }


        return self::fetch_api_token();
    }

    /**
     * Function fectch token from tracktik api
     * @return string
     */

    private static function fetch_api_token(): string
    {

        $resp = self::makeRequest(
            HttpMethodsEnum::POST,
            (string)Config::get('app.tracktik_token_api'),
            [
                'grant_type' => Config::get('app.tracktik_api_grant_type'),
                'client_id' => Config::get('app.tracktik_api_client_id'),
                'client_secret' => Config::get('app.tracktik_api_client_secret'),
                'username' => Config::get('app.tracktik_api_username'),
                'password' => Config::get('app.tracktik_api_password')
            ],
            null,
            true
        );

        //clean up

        ApiToken::truncate();;

        $token = new ApiToken();

        $token->token = $resp['access_token'];
        $token->expires_in = $resp['expires_in'];
        $token->refresh_token = $resp['refresh_token'];

        return ($token->save()) ?
            $token->token
            : throw new HttpResponseException(
                Response::error($error['error'] ?? 'Unsuccessful request', HttpCode::HTTP_BAD_REQUEST)
            );
    }
}
