<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\OpenAIStreamClientService;
use App\Service\RagService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatAction extends Controller
{
    public function __construct(
        private readonly RagService $ragService,
        private readonly OpenAIStreamClientService $openAIClientService
    ) {
    }

    public function __invoke(Request $request): StreamedResponse {
        $question = $request->get('question');
        $context = $this->ragService->searchContext($question);

        return response()->stream(function () use ($question, $context) {
            $stream = $this->openAIClientService->handle($question, $context);

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
