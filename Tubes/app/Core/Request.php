<?php

class Request
{
    public static function jsonBody(): array
    {
        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);
        return is_array($data) ? $data : [];
    }

    public static function method(): string
    {
        $method = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $override = $_SERVER["HTTP_X_HTTP_METHOD_OVERRIDE"] ?? "";

        return strtoupper($override ?: $method);
    }
}
