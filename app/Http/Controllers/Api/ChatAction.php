<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\EmbeddingService;
use App\Service\RagService;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatAction extends Controller
{
    public function __invoke(Request $request, RagService $rag, EmbeddingService $embeddingService): StreamedResponse
    {
        $pergunta = $request->get('pergunta');
        $contexto = $rag->searchContext($pergunta);

        return response()->stream(function () use ($pergunta, $contexto) {
            $stream = OpenAI::chat()->createStreamed([
                'model' => 'deepseek/deepseek-chat-v3-0324:free',
                'stream' => true,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Você é um assistente jurídico especializado em direito trabalhista. Use o seguinte conteúdo como base:\n\n{$contexto}",
                    ],
                    [
                        'role' => 'user',
                        'content' => $pergunta,
                    ],
                ],
            ]);

            foreach ($stream as $chunk) {
                if (isset($chunk->choices[0]->delta->content)) {
                    echo $chunk->choices[0]->delta->content;
                    ob_flush();
                    flush();
                }
            }
        });
    }
}
