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
        array $headers = [],
        bool $json = false
    ) {
        parent::__construct($this->format($data, $errors, $status, $message), $status, $headers, $json);
    }

    private function format(?array $data, ?array $errors, int $status, string $message): array
    {
        return [
            'message' => $message,
            'code' => $status,
            'data' => $data ?? [],
            'errors' => $errors ?? [],
        ];
    }
}