<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzlerException;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('deepseek.api_key');
        $this->apiUrl = config('deepseek.api_url');
    }

    public function sendMessage($message)
    {
        try {
            Log::info('Sending request to DeepSeek API:', ['message' => $message]);

            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek-chat', // Replace with the correct model name
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $message,
                        ],
                    ],
                    // Add other required fields if specified in the API documentation
                    // Example: 'model' => 'gpt-3.5-turbo',

                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Log::info('Sending request to DeepSeek API', [
                'url' => $this->apiUrl,
                'api_key' => substr($this->apiKey, 0, 5) . '******',
                'message' => $message
            ]);
            

            return $responseBody;
        } catch (\Exception $e) {
            Log::error('DeepSeek API Error:', ['error' => $e->getMessage()]);
            return null;
        }
    }
}