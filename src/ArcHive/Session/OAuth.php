<?php
namespace ArcHive\Session;

use stdClass;
use const CURLOPT_HEADER;
use const CURLOPT_POST;
use const CURLOPT_RETURNTRANSFER;

class OAuth {

    private const CLIENT_ID = 'ZwupJErTOFyVCN7ZoPIOv0t1CAwC8Z2l';
    private const CLIENT_SECRET = 'LXfPV0cPl3E8krAmf6D89WCSzh8ZKd9vByewN1juhwD8Ek6H0R0IAIBqhpBSYkyP';
    private const REDIRECT_URI = 'http://localhost:8000/oauth/edu-focus';
    private const SCOPES = ['base', 'email', 'personal'];

    public static function makeOAuthUrl(): string
    {
        $url = 'https://y.edu-focus.org/oauth/authorize';
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => self::CLIENT_ID,
            'redirect_uri' => self::REDIRECT_URI,
            'scope' => implode(' ', self::SCOPES),
            'prompt' => 'none'
        ]);

        return $url . '?' . $query;
    }

    public static function exchangeCode($code)
    {
        $tokenUrl = 'https://y.edu-focus.org/oauth/token';
        $credentials = self::CLIENT_ID . ':' . self::CLIENT_SECRET;
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => self::REDIRECT_URI
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $tokenUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . base64_encode($credentials)
            ]
        ]);

        $response = curl_exec($ch);

        if (curl_getinfo($ch)['http_code'] !== 200) {
            return false;
        }

        $payload = json_decode($response);
        return [$payload->access_token, $payload->refresh_token];
    }

    public static function getUser($token): ?stdClass
    {
        if ($token === null) {
            return null;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.edu-focus.org/users/@current',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . $token
            ]
        ]);

        $result = json_decode(curl_exec($ch));

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {
            return $result->payload;
        }
        return null;
    }

}