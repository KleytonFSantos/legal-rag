<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class RagService
{
    public function __construct(protected EmbeddingService $embedder) {}

    public function searchContext(string $pergunta, int $limite = 5): string
    {
        $queryEmbedding = $this->embedder->embed([$pergunta]);

        $resultados = DB::table('documents')
            ->orderByRaw('embedding <#> ? ASC', [json_encode($queryEmbedding)])
            ->limit($limite)
            ->pluck('text');

        return $resultados->implode("\n\n---\n\n");
    }
}
