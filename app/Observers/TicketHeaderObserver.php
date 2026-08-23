<?php

namespace App\Observers;

use App\Models\TicketHeader;
use App\Services\TelegramNotificationService;

class TicketHeaderObserver
{
    /**
     * Handle the TicketHeader "updated" event.
     */
    public function updated(TicketHeader $ticket): void
    {

        /*
         * Status changed.
         */
        if ($ticket->wasChanged('status')) {
            $oldStatus =
                $ticket->getOriginal('status');
            app(
                TelegramNotificationService::class
            )->notifyTicketStatusChanged($ticket, $oldStatus);
        }

        /*
         * Ticket closed.
         */
        if (
            $ticket->wasChanged('status') &&
            $ticket->status === 'Closed'
        ) {

            app(
                TelegramNotificationService::class
            )->notifyTicketClosed(
                    $ticket
                );
        }
    }
}