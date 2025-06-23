<?php

namespace App\Service;

use App\Client\SentenceTransformerClientService;

final readonly class EmbeddingService
{
    public function __construct(private SentenceTransformerClientService $clientService)
    {
    }

    public function embed(array $chunks): array
    {
        $response = $this->clientService->handle($chunks);
        return json_decode($response->getBody(), true);
    }
}
