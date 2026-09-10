<?php

namespace App\Http\Controllers;

use App\Models\TelegramAccount;
use App\Models\TelegramLinkToken;
use App\Models\TicketDetail;
use App\Models\TicketHeader;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TelegramController extends Controller
{
    public function __construct(
        protected TelegramService $telegram
    ) {
    }

    protected function formatDate($date): string
    {
        return $date ? $date->format('M d, Y h:i A') : 'N/A';
    }
    /**
     * Telegram webhook endpoint.
     */
    public function webhook(Request $request)
    {
        /*
         * Verify Telegram webhook secret.
         */
        $secret =
            config('services.telegram.webhook_secret');

        if (
            $secret &&
            !hash_equals(
                $secret,
                (string) $request->header(
                    'X-Telegram-Bot-Api-Secret-Token'
                )
            )
        ) {
            abort(403);
        }

        $update = $request->all();

        try {
            /*
             * Normal message.
             */
            if (isset($update['message'])) {
                $this->handleMessage(
                    $update['message']
                );
            }

            /*
             * Inline button.
             */
            if (isset($update['callback_query'])) {
                $this->handleCallbackQuery(
                    $update['callback_query']
                );
            }

        } catch (\Throwable $e) {
            Log::error(
                'Telegram webhook error',
                [
                    'error' => $e->getMessage(),
                    'update' => $update,
                ]
            );
        }

        /*
         * Telegram expects a successful response.
         */
        return response()->json([
            'ok' => true,
        ]);
    }

    /**
     * Process incoming Telegram message.
     */
    protected function handleMessage(array $message): void
    {
        $chatId =
            $message['chat']['id'] ?? null;

        $telegramUserId =
            (string) (
                $message['from']['id'] ?? ''
            );

        if (!$chatId || !$telegramUserId) {
            return;
        }

        $text =
            trim($message['text'] ?? '');

        /*
         * /start
         */
        if (
            str_starts_with(
                strtolower($text),
                '/start'
            )
        ) {

            $parts =
                preg_split(
                    '/\s+/',
                    $text,
                    2
                );

            $token =
                $parts[1] ?? null;

            $this->handleStart(
                $chatId,
                $telegramUserId,
                $token,
                $message
            );

            return;
        }

        /*
         * Get linked account.
         */
        $account = TelegramAccount::with('user')
            ->where('telegram_user_id', $telegramUserId)
            ->where('status', 'Active')
            ->first();

        if (!$account) {

            $this->telegram->sendMessage(
                $chatId,
                "❌ <b>Account Not Connected</b>\n\n" .
                "Your Telegram account is not connected " .
                "to an ISMS account.\n\n" .
                "Please login to ISMS and select " .
                "<b>Connect Telegram</b>."
            );

            return;
        }

        /*
         * /help
         */
        if (
            strtolower($text) === '/help'
        ) {
            $this->sendHelp($chatId);
            return;
        }

        /*
         * /tickets
         */
        if (
            strtolower($text) === '/tickets'
        ) {

            $this->sendMyTickets(
                $chatId,
                $account->user_id
            );

            return;
        }

        /*
         * /ticket TICKET-NUMBER
         */
        if (
            preg_match(
                '/^\/ticket\s+(.+)$/i',
                $text,
                $matches
            )
        ) {
            $ticketNumber = trim($matches[1]);

            $this->sendTicket(
                $chatId,
                $account->user_id,
                $ticketNumber
            );
            return;
        }
        /*
         * Unknown command.
         */
        $this->sendMenu($chatId);
    }

    /**
     * Handle account linking.
     */
    protected function handleStart($chatId, string $telegramUserId, ?string $token, array $message): void
    {
        /*
         * Normal /start without linking token.
         */
        if (!$token) {
            $account = TelegramAccount::where('telegram_user_id', $telegramUserId)
                ->where('status', 'Active')
                ->first();

            if ($account) {
                $this->sendMenu($chatId);
            } else {
                $this->telegram->sendMessage(
                    $chatId,
                    "👋 <b>Welcome to ISMS Support</b>\n\n" .
                    "Your Telegram account is not yet linked " .
                    "to an ISMS account.\n\n" .
                    "Please login to the ISMS website and " .
                    "select <b>Connect Telegram</b>."
                );
            }

            return;
        }

        /*
         * Find valid linking token.
         */
        $linkToken =
            TelegramLinkToken::where('token', $token)
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->first();

        if (!$linkToken) {

            $this->telegram->sendMessage(
                $chatId,
                "❌ <b>Invalid or Expired Link</b>\n\n" .
                "Please generate a new Telegram connection " .
                "link from the ISMS website."
            );

            return;
        }

        /*
         * Telegram user information.
         */
        $from = $message['from'] ?? [];

        /*
         * One Telegram account cannot belong
         * to multiple ISMS users.
         */
        $existing =
            TelegramAccount::where('telegram_user_id', $telegramUserId)
                ->where('user_id', '!=', $linkToken->user_id)
                ->first();

        if ($existing) {
            $this->telegram->sendMessage(
                $chatId,
                "❌ This Telegram account is already " .
                "connected to another ISMS account."
            );

            return;
        }

        /*
         * Create/update connection.
         */
        DB::transaction(function () use ($linkToken, $telegramUserId, $from) {

            TelegramAccount::updateOrCreate(
                [
                    'user_id' =>
                        $linkToken->user_id,
                ],
                [
                    'telegram_user_id' =>
                        $telegramUserId,

                    'telegram_username' =>
                        $from['username'] ?? null,

                    'telegram_first_name' =>
                        $from['first_name'] ?? null,

                    'telegram_last_name' =>
                        $from['last_name'] ?? null,

                    'linked_at' => now(),

                    'status' => 'Active',
                ]
            );

            $linkToken->update([
                'used_at' => now(),
            ]);
        });

        $this->telegram->sendMessage(
            $chatId,
            "✅ <b>Telegram Connected Successfully!</b>\n\n" .
            "Your Telegram account is now connected to ISMS.\n\n" .
            "You can now:\n" .
            "🎫 View your tickets\n" .
            "🔎 Check ticket status\n" .
            "🔔 Receive ticket updates\n" .
            "💬 Receive support replies\n\n" .
            "Use /help to get started."
        );
    }

    /**
     * Main menu.
     */
    protected function sendMenu($chatId): void
    {
        $this->telegram->sendMessage(
            $chatId,
            "👋 <b>ISMS Support</b>\n\n" .
            "What would you like to do?",
            [
                [
                    [
                        'text' => '🎫 My Tickets',
                        'callback_data' => 'my_tickets',
                    ],
                ],
                [
                    [
                        'text' => '❓ Help',
                        'callback_data' => 'help',
                    ],
                ],
            ]
        );
    }

    /**
     * Help.
     */
    protected function sendHelp($chatId): void
    {
        $this->telegram->sendMessage(
            $chatId,
            "❓ <b>ISMS Support Bot</b>\n\n" .

            "<b>Available commands:</b>\n\n" .

            "/tickets - View your tickets\n" .

            "/ticket TICKET-NUMBER - Check a ticket\n" .

            "/help - Show this help\n\n" .

            "You can only view tickets created " .
            "under your ISMS account."
        );
    }

    /**
     * Get user's tickets.
     */
    protected function sendMyTickets(
        $chatId,
        int $userId
    ): void {

        /*
         * SECURITY:
         *
         * Only tickets owned by this user.
         */
        $tickets =
            TicketHeader::where('user_id', $userId)
                ->orderByDesc('date_created')
                ->limit(10)
                ->get();

        if ($tickets->isEmpty()) {

            $this->telegram->sendMessage(
                $chatId,
                "📋 <b>My Tickets</b>\n\n" .
                "You don't have any tickets yet."
            );

            return;
        }

        $message =
            "📋 <b>My Tickets</b>\n\n";

        $keyboard = [];

        foreach ($tickets as $ticket) {

            $message .= "🎫 <b>" . e($ticket->ticket_number) . "</b>\n" .

                e(Str::limit($ticket->description, 120)) . "\n" .

                "Status: <b>" . e($ticket->status) . "</b>\n\n";

            $keyboard[] = [
                [
                    'text' =>
                        '🎫 ' .
                        $ticket->ticket_number,

                    'callback_data' =>
                        'ticket:' .
                        $ticket->id,
                ],
            ];
        }
        $this->telegram->sendMessage($chatId, $message, $keyboard);
    }

    /**
     * Get a specific ticket.
     */
    protected function sendTicket($chatId, int $userId, string $ticketNumber): void
    {

        /*
         * CRITICAL SECURITY CHECK.
         *
         * The ticket must belong to the
         * authenticated Telegram user's
         * ISMS account.
         */
        $ticket = TicketHeader::where('ticket_number', $ticketNumber)
            ->where('user_id', $userId)
            ->first();

        if (!$ticket) {

            $this->telegram->sendMessage(
                $chatId,
                "❌ <b>Ticket Not Found</b>\n\n" .
                "The ticket <b>" .
                e($ticketNumber) .
                "</b> was not found in your tickets."
            );

            return;
        }

        $this->sendTicketDetails(
            $chatId,
            $ticket
        );
    }

    /**
     * Display ticket details.
     */
    protected function sendTicketDetails($chatId, TicketHeader $ticket): void
    {
        $message =
            "🎫 <b>" . e($ticket->ticket_number) . "</b>\n\n" .

            "📝 <b>Description</b>\n" . e($ticket->description) . "\n\n" .

            "📌 Status: <b>" . e($ticket->status) . "</b>\n" .
            "⚡ Priority: <b>" . e($ticket->priority) . "</b>\n" .

            "📅 Created: " . optional($this->formatDate($ticket->date_created));

        if ($ticket->date_closed) {

            $message .= "\n🔒 Closed: " . optional(
                $this->formatDate($ticket->date_closed)
            );
        }

        $keyboard = [
            [
                [
                    'text' => '💬 View Conversation',
                    'callback_data' =>
                        'details:' . $ticket->id,
                ],
            ],
            [
                [
                    'text' => '📋 My Tickets',
                    'callback_data' =>
                        'my_tickets',
                ],
            ],
        ];

        $this->telegram->sendMessage(
            $chatId,
            $message,
            $keyboard
        );
    }

    /**
     * Handle inline keyboard.
     */
    protected function handleCallbackQuery(array $callback): void
    {

        $callbackId = $callback['id'];

        $chatId = $callback['message']['chat']['id'] ?? null;

        $telegramUserId = (string) (
            $callback['from']['id']
            ?? '');

        $data = $callback['data'] ?? '';

        /*
         * Find Telegram account.
         */
        $account = TelegramAccount::where('telegram_user_id', $telegramUserId)
            ->where('status', 'Active')
            ->first();

        if (!$account) {
            $this->telegram->answerCallbackQuery($callbackId, 'Account not connected.');
            return;
        }

        $this->telegram->answerCallbackQuery($callbackId);

        /*
         * My tickets.
         */
        if ($data === 'my_tickets') {
            $this->sendMyTickets($chatId, $account->user_id);
            return;
        }

        /*
         * Help.
         */
        if ($data === 'help') {
            $this->sendHelp($chatId);
            return;
        }

        /*
         * Ticket button.
         */
        if (
            preg_match(
                '/^ticket:(\d+)$/',
                $data,
                $matches
            )
        ) {

            $ticketId =
                (int) $matches[1];

            /*
             * CRITICAL:
             *
             * Ticket ID AND user_id.
             */
            $ticket =
                TicketHeader::where(
                    'id',
                    $ticketId
                )
                    ->where(
                        'user_id',
                        $account->user_id
                    )
                    ->first();

            if (!$ticket) {
                $this->telegram->sendMessage(
                    $chatId,
                    "❌ You are not authorized to view this ticket."
                );

                return;
            }

            $this->sendTicketDetails(
                $chatId,
                $ticket
            );

            return;
        }

        /*
         * Ticket details.
         */
        if (
            preg_match(
                '/^details:(\d+)$/',
                $data,
                $matches
            )
        ) {

            $ticketId =
                (int) $matches[1];

            /*
             * SECURITY:
             *
             * User ownership is checked.
             */
            $ticket =
                TicketHeader::where(
                    'id',
                    $ticketId
                )
                    ->where(
                        'user_id',
                        $account->user_id
                    )
                    ->first();

            if (!$ticket) {

                $this->telegram->sendMessage(
                    $chatId,
                    "❌ You are not authorized to view this ticket."
                );

                return;
            }

            $this->sendConversation(
                $chatId,
                $ticket
            );
        }
    }

    /**
     * Display ticket conversation.
     */
    protected function sendConversation(
        $chatId,
        TicketHeader $ticket
    ): void {

        $details =
            TicketDetail::where(
                'ticket_id',
                $ticket->id
            )
                ->orderBy(
                    'date_created'
                )
                ->limit(20)
                ->get();

        $message =
            "💬 <b>Conversation</b>\n\n" .

            "🎫 <b>" .
            e($ticket->ticket_number) .
            "</b>\n\n";

        if ($details->isEmpty()) {

            $message .=
                "No conversation messages yet.";

        } else {

            foreach ($details as $detail) {

                $message .= "━━━━━━━━━━━━━━\n" . e($detail->message) . "\n" .

                    "📅 " . optional($this->formatDate($detail->date_created)) . "\n\n";
            }
        }

        $this->telegram->sendMessage(
            $chatId,
            $message,
            [
                [
                    [
                        'text' => '🎫 Ticket',
                        'callback_data' =>
                            'ticket:' . $ticket->id,
                    ],
                ],
            ]
        );
    }
}