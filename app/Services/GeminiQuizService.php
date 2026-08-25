<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiQuizService
{
    protected string $apiKey;
    protected string $model = 'gemini-flash-latest';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generateQuestions(string $materialText, int $count = 5): array
    {
        $prompt = <<<PROMPT
        Kamu adalah asisten pembuat soal quiz. Berdasarkan materi berikut, buat {$count} soal pilihan ganda (4 opsi jawaban, hanya 1 yang benar).

        Materi:
        {$materialText}

        Jawab HANYA dalam format JSON array seperti ini, tanpa teks tambahan apapun:
        [
          {
            "question": "isi pertanyaan",
            "options": ["opsi A", "opsi B", "opsi C", "opsi D"],
            "correct_index": 0
          }
        ]
        PROMPT;

        $response = Http::withHeader('x-goog-api-key', $this->apiKey)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
            ]);

        if ($response->failed()) {
            throw new \Exception('Gagal menghubungi Gemini API: ' . $response->body());
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        // Bersihkan kalau Gemini bungkus jawabannya dengan ```json ... ```
        $text = preg_replace('/```json|```/', '', $text);
        $text = trim($text);

        $questions = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($questions)) {
            throw new \Exception('Gagal parsing hasil dari Gemini: ' . $text);
        }

        return $questions;
    }
}