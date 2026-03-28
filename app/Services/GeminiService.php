<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $model;
    protected $apiVersion;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-1.5-pro');
        $this->apiVersion = config('services.gemini.api_version', 'v1beta'); // Try v1beta first
    }

    public function sendMessage($message, $retryCount = 0)
    {
        try {
            $apiUrl = "https://generativelanguage.googleapis.com/{$this->apiVersion}/models/{$this->model}:generateContent?key=" . $this->apiKey;

            Log::info('Sending request to Gemini API:', [
                'message' => $message,
                'model' => $this->model,
                'api_version' => $this->apiVersion,
                'url' => $apiUrl
            ]);

            $response = $this->client->post($apiUrl, [
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

            if (!empty($responseBody['candidates'][0]['content']['parts'][0]['text'])) {
                return $responseBody['candidates'][0]['content']['parts'][0]['text'];
            } else {
                return "No response from Gemini.";
            }

        } catch (\Exception $e) {
            // If it fails with v1beta, try v1 as fallback
            if ($retryCount === 0 && $this->apiVersion === 'v1beta') {
                Log::info('Retrying with v1 API version');
                $this->apiVersion = 'v1';
                return $this->sendMessage($message, $retryCount + 1);
            }

            // If it still fails, try with gemini-pro as fallback
            if ($retryCount === 1 && $this->model !== 'gemini-pro') {
                Log::info('Retrying with gemini-pro model');
                $this->model = 'gemini-pro';
                $this->apiVersion = 'v1';
                return $this->sendMessage($message, $retryCount + 1);
            }

            Log::error('Gemini API Error:', ['error' => $e->getMessage()]);
            return "Error communicating with Gemini API: " . $e->getMessage();
        }
    }

    public function listModels()
    {
        try {
            $url = "https://generativelanguage.googleapis.com/v1/models?key=" . $this->apiKey;
            $response = $this->client->get($url);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error listing models:', ['error' => $e->getMessage()]);
            return null;
        }
    }
}