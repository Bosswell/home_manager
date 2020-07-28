<?php


namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct(
        string $message,
        int $status = 200,
        ?array $data = null,
        ?array $errors = null,
        string $debugMessage = '',
        array $headers = [],
        bool $json = false
    ) {
        parent::__construct($this->format($message, $data, $errors, $status, $debugMessage), $status, $headers, $json);
    }

    private function format(string $message, ?array $data, ?array $errors, int $status, string $debugMessage = ''): array
    {
        return [
            'message' => $message,
            'code' => $status,
            'data' => $data ?? [],
            'errors' => $errors ?? [],
            'debug' => $debugMessage
        ];
    }
}