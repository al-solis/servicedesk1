<?php

namespace App\Services;

use App\Models\TicketHeader;
use App\Models\TelegramAccount;

class TelegramNotificationService
{
    public function __construct(
        protected TelegramService $telegram
    ) {
    }

    /**
     * Send notification to ticket owner.
     */
    public function notifyTicketStatusChanged(
        TicketHeader $ticket,
        ?string $oldStatus = null
    ): void {

        $account = TelegramAccount::where(
            'user_id',
            $ticket->user_id
        )
            ->where('status', 'Active')
            ->first();

        if (!$account) {
            return;
        }

        $message =
            "🔔 <b>ISMS Ticket Update</b>\n\n" .

            "🎫 Ticket: <b>" .
            e($ticket->ticket_number) .
            "</b>\n\n" .

            "📝 " .
            e($ticket->description) .
            "\n\n";

        if ($oldStatus) {

            $message .=
                "Status: <b>" .
                e($oldStatus) .
                "</b> → <b>" .
                e($ticket->status) .
                "</b>\n";
        } else {

            $message .=
                "Status: <b>" .
                e($ticket->status) .
                "</b>\n";
        }

        $message .=
            "Priority: <b>" .
            e($ticket->priority) .
            "</b>\n\n" .

            "Use <b>/ticket " .
            e($ticket->ticket_number) .
            "</b> to view the ticket.";

        $this->telegram->sendMessage(
            $account->telegram_user_id,
            $message,
            [
                [
                    [
                        'text' => '🎫 View Ticket',
                        'callback_data' =>
                            'ticket:' . $ticket->id,
                    ],
                ],
                [
                    [
                        'text' => '📋 My Tickets',
                        'callback_data' =>
                            'my_tickets',
                    ],
                ],
            ]
        );
    }

    public function notifyTicketCreated(TicketHeader $ticket): void
    {
        $account = TelegramAccount::where('user_id', $ticket->user_id)
            ->where('status', 'Active')
            ->first();

        if (!$account) {
            return;
        }

        $message =
            "🎫 <b>Ticket Created</b>\n\n" .
            "Your support ticket has been created successfully.\n\n" .
            "🎫 Ticket: <b>" . e($ticket->ticket_number) . "</b>\n" .
            "📝 " . e(\Illuminate\Support\Str::limit($ticket->description, 120)) . "\n" .
            "📌 Status: <b>" . e($ticket->status) . "</b>\n" .
            "⚡ Priority: <b>" . e($ticket->priority) . "</b>\n\n" .
            "You will receive updates as your ticket progresses.";

        $this->telegram->sendMessage(
            $account->telegram_user_id,
            $message,
            [
                [
                    [
                        'text' => '🎫 View Ticket',
                        'callback_data' => 'ticket:' . $ticket->id,
                    ],
                ],
            ]
        );
    }

    /**
     * Notify ticket owner that a support reply was added.
     */
    public function notifyNewReply(
        TicketHeader $ticket,
        string $message,
        ?int $detailUserId = null
    ): void {

        /*
         * Don't notify the requester about their own message.
         */
        if (
            $detailUserId !== null &&
            $detailUserId === $ticket->user_id
        ) {
            return;
        }

        $account = TelegramAccount::where(
            'user_id',
            $ticket->user_id
        )
            ->where('status', 'Active')
            ->first();

        if (!$account) {
            return;
        }

        $messageText =
            "💬 <b>New Reply to Your Ticket</b>\n\n" .

            "🎫 Ticket: <b>" .
            e($ticket->ticket_number) .
            "</b>\n\n" .

            e($message);

        $this->telegram->sendMessage(
            $account->telegram_user_id,
            $messageText,
            [
                [
                    [
                        'text' => '🎫 View Ticket',
                        'callback_data' =>
                            'ticket:' . $ticket->id,
                    ],
                ],
            ]
        );
    }

    /**
     * Notify ticket owner that ticket was closed.
     */
    public function notifyTicketClosed(
        TicketHeader $ticket
    ): void {

        $account = TelegramAccount::where(
            'user_id',
            $ticket->user_id
        )
            ->where('status', 'Active')
            ->first();

        if (!$account) {
            return;
        }

        $message =
            "🔒 <b>Ticket Closed</b>\n\n" .

            "🎫 Ticket: <b>" .
            e($ticket->ticket_number) .
            "</b>\n\n" .

            "Your support request has been closed.\n\n" .

            "Thank you for using ISMS Support.";

        $this->telegram->sendMessage(
            $account->telegram_user_id,
            $message,
            [
                [
                    [
                        'text' => '🎫 View Ticket',
                        'callback_data' =>
                            'ticket:' . $ticket->id,
                    ],
                ],
            ]
        );
    }

    public function notifySupportTeamNewTicket(
        TicketHeader $ticket,
        $userIds
    ): void {
        $accounts = TelegramAccount::whereIn('user_id', $userIds)
            ->where('status', 'Active')
            ->get();

        foreach ($accounts as $account) {
            $message =
                "🆕 <b>New Ticket Assigned to Your Team</b>\n\n" .
                "🎫 Ticket: <b>" . e($ticket->ticket_number) . "</b>\n" .
                "👤 From: " . e(optional($ticket->user)->fname . ' ' . optional($ticket->user)->lname) . "\n" .
                "📝 " . e(\Illuminate\Support\Str::limit($ticket->description, 120)) . "\n" .
                "⚡ Priority: <b>" . e($ticket->priority) . "</b>";

            $this->telegram->sendMessage(
                $account->telegram_user_id,
                $message,
                [
                    [
                        [
                            'text' => '🎫 View Ticket',
                            'callback_data' => 'ticket:' . $ticket->id,
                        ],
                    ],
                ]
            );
        }
    }
}