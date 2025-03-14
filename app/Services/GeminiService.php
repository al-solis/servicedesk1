<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $this->apiKey;
    }

    public function sendMessage($message)
    {
        try {
            Log::info('Sending request to Gemini API:', ['message' => $message]);

            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $message]
                            ]
                        ]
                    ]
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Log::info('Gemini API response:', ['response' => $responseBody]);

            // Extract response text
            if (!empty($responseBody['candidates'][0]['content']['parts'][0]['text'])) {
                return $responseBody['candidates'][0]['content']['parts'][0]['text'];
            } else {
                return "No response from Gemini.";
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Error:', ['error' => $e->getMessage()]);
            return "Error communicating with Gemini API.";
        }
    }
}
