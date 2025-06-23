<?php

namespace App\Service;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\StreamResponse;

final readonly class OpenAIStreamClientService
{
    const string MODEL = 'deepseek/deepseek-chat-v3-0324:free';
    public function handle(string $question, string $context): StreamResponse
    {
        return OpenAI::chat()->createStreamed([
            'model' => self::MODEL,
            'stream' => true,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Você é um assistente jurídico especializado "
                        . "em direito trabalhista. Use o seguinte conteúdo como base: " .
                        "\n\n$context"
                ],
                [
                    'role' => 'user',
                    'content' => $question,
                ],
            ],
        ]);
    }
}
