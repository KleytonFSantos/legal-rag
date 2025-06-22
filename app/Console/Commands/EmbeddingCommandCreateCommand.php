<?php

namespace App\Console\Commands;

use App\Service\EmbeddingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\PdfToText\Pdf;

class EmbeddingCommandCreateCommand extends Command
{
    protected $signature = 'document:index';
    protected $description = 'Indexa um documento como embedding';

    public function handle(EmbeddingService $embedder): void
    {
        $filePath = Storage::path('2021_Direito_Civil_Teoria_Geral_dos_Contratos_e_Contratos_em_Espécie.pdf');
        $text = Pdf::getText($filePath);
        $text = mb_convert_encoding($text, 'UTF-8', 'auto');

        // Dividir o texto em partes menores (chunks) de no máximo 20 parágrafos
        $chunks = Str::of($text)->split('/(\r?\n){2,}/')->chunk(20);

        $finalVector = [];
        foreach ($chunks as $chunkGroup) {
            $chunk = $chunkGroup->join("\n\n");
            $finalVector[] = $chunk;
            $this->info('Indexado chunk com ' . Str::length($chunk) . ' caracteres');
        }

        $embeddedChunks = $embedder->embed($finalVector);

        foreach ($embeddedChunks as $index => $embeddedChunk) {
            $chunkText = $finalVector[$index];

            DB::table('documents')->insert([
                'text' => $chunkText,
                'embedding' => '['.$embeddedChunk.']',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $this->info('Salvo ' . count($embeddedChunks) . ' embeddings, média do embedding: ' . $embeddedChunk ?? '');
    }
}
