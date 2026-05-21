<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jwt {

    private $secret;

    public function __construct()
    {
        $CI =& get_instance();
        $this->secret = $CI->config->item('jwt_secret_key') ?: 'ci3-hmvc-rest-api-secret';
    }

    public function create($payload = array())
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + 86400;

        $header = array('typ' => 'JWT', 'alg' => 'HS256');
        $segments = array(
            $this->base64url_encode(json_encode($header)),
            $this->base64url_encode(json_encode($payload))
        );

        $signature = hash_hmac('sha256', implode('.', $segments), $this->secret, TRUE);
        $segments[] = $this->base64url_encode($signature);

        return implode('.', $segments);
    }

    public function verify($token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return FALSE;
        }

        list($header, $payload, $signature) = $parts;
        $valid_signature = $this->base64url_encode(hash_hmac('sha256', $header . '.' . $payload, $this->secret, TRUE));

        if (!hash_equals($valid_signature, $signature)) {
            return FALSE;
        }

        $data = json_decode($this->base64url_decode($payload));

        if (!$data || (isset($data->exp) && $data->exp < time())) {
            return FALSE;
        }

        return $data;
    }

    public function get_token_from_request()
    {
        $headers = function_exists('getallheaders') ? getallheaders() : array();
        $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if (!$authorization && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (!$authorization && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authorization = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        return preg_match('/Bearer\s+(.*)$/i', (string) $authorization, $matches) ? trim($matches[1]) : null;
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
