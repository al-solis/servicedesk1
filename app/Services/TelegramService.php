<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;

    protected string $apiUrl;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');

        $this->apiUrl =
            'https://api.telegram.org/bot' .
            $this->token;
    }

    /**
     * Call Telegram Bot API.
     */
    public function call(string $method, array $data = [])
    {
        $response = Http::timeout(15)
            ->post(
                $this->apiUrl . '/' . $method,
                $data
            );

        if (!$response->successful()) {

            Log::error(
                'Telegram API HTTP Error',
                [
                    'method' => $method,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]
            );
        }

        return $response;
    }

    /**
     * Send a Telegram message.
     */
    public function sendMessage(string|int $chatId, string $message, ?array $keyboard = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ];

        if ($keyboard !== null) {

            $data['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

        return $this->call(
            'sendMessage',
            $data
        );
    }

    /**
     * Answer callback query.
     */
    public function answerCallbackQuery(string $callbackQueryId, ?string $text = null)
    {
        $data = [
            'callback_query_id' => $callbackQueryId,
        ];

        if ($text !== null) {
            $data['text'] = $text;
        }

        return $this->call(
            'answerCallbackQuery',
            $data
        );
    }

    /**
     * Edit existing Telegram message.
     */
    public function editMessageText(
        string|int $chatId,
        int $messageId,
        string $message,
        ?array $keyboard = null
    ) {
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ];

        if ($keyboard !== null) {

            $data['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

        return $this->call(
            'editMessageText',
            $data
        );
    }
}