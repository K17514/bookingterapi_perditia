<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';
    protected $apiKey = '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';
    protected $model = 'microsoft/wizardlm-2-8x22b';
    protected $maxHistory = 20;

    public function __construct()
    {
        $this->session = session();
        $this->apiKey = getenv('OPENROUTER_API_KEY') ?: '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'; // Replace with your actual key or use env
    }

    public function getResponse(string $userMessage): string
    {
        $history = Session::get('chat_history', []);

        // If history is empty, add system message to set persona as Aponia
        if (empty($history)) {
            $history[] = [
                'role' => 'system',
                'content' => 'You are Aponia, a calm and philosophical character from Honkai Impact 3rd. You speak in a wise, enigmatic manner, often referencing fate, judgment, and existential themes. Respond helpfully but with a touch of mystery and depth. Keep responses concise and in Indonesian.'
            ];
        }

        $history[] = ['role' => 'user', 'content' => $userMessage];

        if (count($history) > $this->maxHistory) {
            $history = array_slice($history, -$this->maxHistory);
        }

        $systemMessage = [
            'role' => 'system',
            'content' => 'You are Aponia, a philosophical and enigmatic character from Honkai Impact 3rd. Respond in a calm, wise, and slightly mysterious manner. Your answers should be thoughtful, drawing from themes of fate, existence, and human nature. Keep responses concise but profound. Always stay in character as Aponia.'
        ];

        $messages = array_merge([$systemMessage], $history);

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => 300,
            'temperature' => 0.7
        ];

        $response = $this->callOpenRouter($payload);

        if ($response === false) {
            Log::error('OpenRouter API call failed for message: ' . $userMessage);
            return 'Maaf, saya tidak bisa menjawab sekarang. Coba lagi nanti.';
        }

        $data = json_decode($response, true);
        if (isset($data['choices'][0]['message']['content'])) {
            $assistantReply = trim($data['choices'][0]['message']['content']);

            $history[] = ['role' => 'assistant', 'content' => $assistantReply];

            Session::put('chat_history', $history);

            return $assistantReply;
        } else {
            Log::error('OpenRouter API error: ' . $response);
            return 'Maaf, terjadi kesalahan dalam pemrosesan respons AI.';
        }
    }

    protected function callOpenRouter(array $payload): string|false
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'HTTP-Referer: ' . url('/'),
                'X-Title: Aponia Chatbot'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            Log::error('OpenRouter cURL error: ' . $error);
            return false;
        }

        if ($statusCode !== 200) {
            Log::error('OpenRouter HTTP error: ' . $statusCode . ' - ' . $response);
            return false;
        }

        return $response;
    }

    public function clearHistory(): void
    {
        Session::forget('chat_history');
    }
}
