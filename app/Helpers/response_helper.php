<?php

if (! function_exists('api_success')) {
    /**
     * Build a standard JSON success string.
     *
     * Returns a JSON-encoded envelope with status=true. Use this only in
     * contexts where ResponseInterface is not available (Filters, exception
     * handlers). Controllers should use ApiResponseTrait instead.
     */
    function api_success(mixed $data = null, string $message = 'Success', int $code = 200): string
    {
        $payload = [
            'status'  => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            return '{"status":true,"code":' . $code . ',"message":"' . addslashes($message) . '","data":null}';
        }

        return $json;
    }
}

if (! function_exists('api_error')) {
    /**
     * Build a standard JSON error string.
     *
     * Returns a JSON-encoded envelope with status=false. Use this only in
     * contexts where ResponseInterface is not available (Filters, exception
     * handlers). Controllers should use ApiResponseTrait instead.
     */
    function api_error(string $message, int $code = 400, mixed $errors = null): string
    {
        $payload = [
            'status'  => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            return '{"status":false,"code":' . $code . ',"message":"' . addslashes($message) . '","errors":null}';
        }

        return $json;
    }
}
