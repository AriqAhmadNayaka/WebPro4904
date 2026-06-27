<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jwt
{
    private $secret_key = 'ci3_project_secret_key_ubah_ini';
    private $algorithm = 'HS256';
    private $expiration = 86400;

    public function encode($payload)
    {
        $header = array(
            'typ' => 'JWT',
            'alg' => $this->algorithm
        );

        $payload['iat'] = time();
        $payload['exp'] = time() + $this->expiration;

        $header_encoded = $this->base64url_encode(json_encode($header));
        $payload_encoded = $this->base64url_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $header_encoded . '.' . $payload_encoded, $this->secret_key, TRUE);
        $signature_encoded = $this->base64url_encode($signature);

        return $header_encoded . '.' . $payload_encoded . '.' . $signature_encoded;
    }

    public function decode($token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return FALSE;
        }

        list($header_encoded, $payload_encoded, $signature_encoded) = $parts;

        $signature = $this->base64url_decode($signature_encoded);
        $valid_signature = hash_hmac('sha256', $header_encoded . '.' . $payload_encoded, $this->secret_key, TRUE);

        if (!hash_equals($valid_signature, $signature)) {
            return FALSE;
        }

        $payload = json_decode($this->base64url_decode($payload_encoded), TRUE);

        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return FALSE;
        }

        return $payload;
    }

    public function get_token_from_header()
    {
        $auth_header = null;

        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $auth_header = $headers['Authorization'];
            } elseif (isset($headers['authorization'])) {
                $auth_header = $headers['authorization'];
            }
        }

        if (!$auth_header && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (!$auth_header && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        if (!$auth_header) {
            return FALSE;
        }

        if (preg_match('/Bearer\s+(\S+)/', $auth_header, $matches)) {
            return $matches[1];
        }

        return FALSE;
    }

    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
