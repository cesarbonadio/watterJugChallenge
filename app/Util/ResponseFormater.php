<?php

namespace App\Util;

class ResponseFormater {
    /**
     * Helper function to create a consistent response array.
     *
     * @param string $message The message to return.
     * @param int $status_code The HTTP status code.
     * @param mixed $data The data to return in the response.
     * @param array $metadata Optional metadata to include in the response.
     * @return array The response array.
     */
    public static function response(
        string $message,
        int $status_code,
        array $data = [],
        array $metadata = []
    ): array
    {
        return [
            'message' => $message,
            'status_code' => $status_code,
            'data' => $data,
            'metadata' => (object)$metadata,
        ];
    }
}