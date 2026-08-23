<?php

namespace App\Observers;

use App\Models\TicketDetail;
use App\Services\TelegramNotificationService;

class TicketDetailObserver
{
    public function created(TicketDetail $detail): void
    {
        $ticket = $detail->ticket;
        if (!$ticket) {
            return;
        }

        app(
            TelegramNotificationService::class
        )->notifyNewReply(
                $ticket,
                $detail->message,
                $detail->user_id
            );
    }
}