<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramWebhook extends Command
{
    protected $signature = 'telegram:webhook
                            {action=set : set|delete|info}
                            {--url= : Override webhook URL}';

    protected $description = 'Manage Telegram webhook';

    public function handle()
    {
        $token = config('services.telegram.bot_token');
        $base = "https://api.telegram.org/bot{$token}";

        $action = $this->argument('action');

        if ($action === 'set') {
            $url = $this->option('url')
                ?? config('app.url') . '/api/telegram/webhook';

            $response = Http::post("{$base}/setWebhook", [
                'url' => $url,
                'secret_token' => config('services.telegram.webhook_secret'),
                'allowed_updates' => ['message', 'callback_query'],
            ]);

            $this->info("Setting webhook to: {$url}");
            $this->line($response->body());
            return;
        }

        if ($action === 'delete') {
            $this->line(Http::post("{$base}/deleteWebhook")->body());
            return;
        }

        if ($action === 'info') {
            $this->line(Http::get("{$base}/getWebhookInfo")->body());
            return;
        }
    }
}