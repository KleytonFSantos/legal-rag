<?php

namespace App\Service;

use GuzzleHttp\Client;

class EmbeddingService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://openrouter.ai/']);
    }

    public function embed(array $chunks): array
    {
        $client = new Client([
            'base_uri' => 'https://router.huggingface.co/',
        ]);

        $response = $client->post('hf-inference/models/sentence-transformers/all-MiniLM-L6-v2/pipeline/sentence-similarity', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('HUGGINGFACE_TOKEN'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'inputs' => [
                    'source_sentence' => 'That is a happy person',
                    'sentences' => $chunks,
//                    'sentences' => ['qual a base do direito tributário?']
                ],
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
