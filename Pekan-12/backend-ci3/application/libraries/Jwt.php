<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jwt
{
    // Secret key dipakai untuk membuat dan memverifikasi tanda tangan token.
    private $secret;
    private $algo = 'sha256';
    // Token berlaku 24 jam.
    private $ttl = 86400;

    public function __construct()
    {
        // Ambil secret dari config agar mudah diganti tanpa mengubah library.
        $CI =& get_instance();
        $key = $CI->config->item('jwt_secret_key');

        if (empty($key)) {
            $key = $CI->config->item('encryption_key');
        }

        $this->secret = !empty($key) ? $key : 'ci3-hmvc-rest-api-secret';
    }

    public function create($payload = array())
    {
        // Payload ditambah waktu pembuatan dan waktu kedaluwarsa token.
        $now = time();
        $payload['iat'] = isset($payload['iat']) ? $payload['iat'] : $now;
        $payload['exp'] = isset($payload['exp']) ? $payload['exp'] : ($now + $this->ttl);

        // JWT terdiri dari header, payload, dan signature.
        $header = array('typ' => 'JWT', 'alg' => 'HS256');
        $segments = array(
            $this->base64url_encode(json_encode($header)),
            $this->base64url_encode(json_encode($payload))
        );

        $signature = hash_hmac($this->algo, implode('.', $segments), $this->secret, true);
        $segments[] = $this->base64url_encode($signature);

        return implode('.', $segments);
    }

    public function verify($token)
    {
        // Token valid harus memiliki tiga bagian yang dipisahkan titik.
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new Exception('Invalid token format');
        }

        // Signature dihitung ulang untuk memastikan token tidak dimodifikasi.
        list($header64, $payload64, $signature64) = $parts;
        $signature = $this->base64url_decode($signature64);
        $expected = hash_hmac($this->algo, $header64 . '.' . $payload64, $this->secret, true);

        if (!$this->hash_equals($expected, $signature)) {
            throw new Exception('Invalid token signature');
        }

        $payload = json_decode($this->base64url_decode($payload64));

        if (!$payload) {
            throw new Exception('Invalid token payload');
        }

        // Token ditolak jika sudah melewati waktu kedaluwarsa.
        if (isset($payload->exp) && time() > $payload->exp) {
            throw new Exception('Token has expired');
        }

        return $payload;
    }

    public function get_token_from_request()
    {
        // Token dibaca dari header Authorization dengan format Bearer <token>.
        $headers = function_exists('getallheaders') ? getallheaders() : array();
        $authorization = null;

        foreach ($headers as $name => $value) {
            if (strtolower($name) === 'authorization') {
                $authorization = $value;
                break;
            }
        }

        if (empty($authorization) && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (empty($authorization) && !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authorization = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        if (!empty($authorization) && preg_match('/Bearer\s+(.*)$/i', $authorization, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function base64url_encode($data)
    {
        // Format base64url dipakai oleh standar JWT.
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64url_decode($data)
    {
        $remainder = strlen($data) % 4;

        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function hash_equals($known, $user)
    {
        // Fallback untuk membandingkan signature pada versi PHP lama.
        if (function_exists('hash_equals')) {
            return hash_equals($known, $user);
        }

        if (strlen($known) !== strlen($user)) {
            return false;
        }

        $result = 0;
        for ($i = 0; $i < strlen($known); $i++) {
            $result |= ord($known[$i]) ^ ord($user[$i]);
        }

        return $result === 0;
    }
}
