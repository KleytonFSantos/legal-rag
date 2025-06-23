<?php

namespace App\Client;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

final readonly class SentenceTransformerClientService
{
    public Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('HUGGINGFACE_API_BASE'),
        ]);
    }

    public function handle(array $chunks): ResponseInterface
    {
        return $this->client->post(env('SENTENCE_TRANSFORMER_URI'), [
            'headers' => [
                'Authorization' => 'Bearer ' . env('HUGGINGFACE_TOKEN'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'inputs' => [
                    'source_sentence' => 'Data to vectorization',
                    'sentences' => $chunks,
                ],
            ],
        ]);
    }
}
