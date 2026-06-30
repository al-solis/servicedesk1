<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Twilio\TwiML\MessagingResponse;
use App\Models\User;
use App\Models\TicketHeader;
use App\Models\TicketDetail;

class TwilioController extends Controller
{
    public function webhook(Request $request)
    {
        $incoming = trim($request->input('Body'));
        $response = new MessagingResponse();

        // ===============================
        // STEP 1: USER SENDS EMAIL
        // ===============================
        if (str_starts_with($incoming, 'CHECK_TICKET:')) {

            $email = str_replace('CHECK_TICKET:', '', $incoming);

            $user = User::where('email', $email)->first();

            if (!$user) {
                $response->message("❌ Email not found.");
                return response($response, 200)->header('Content-Type', 'text/xml');
            }

            $tickets = TicketHeader::where('user_id', $user->id)
                ->where('status', '!=', 'Closed')
                ->latest('date_created')
                ->take(5)
                ->get();

            if ($tickets->isEmpty()) {
                $response->message("✅ No open tickets.");
                return response($response, 200)->header('Content-Type', 'text/xml');
            }

            $msg = "🎫 OPEN TICKETS\n\n";

            foreach ($tickets as $t) {
                $msg .= "ID: {$t->ticket_number} | Description: {$t->description} | Status: {$t->status}\n";
            }

            $msg .= "\nReply with ticket ID.";

            $response->message($msg);

            return response($response, 200)->header('Content-Type', 'text/xml');
        }

        // ===============================
        // STEP 2: USER SENDS TICKET ID
        // ===============================
        if ($incoming) {

            // $ticket = TicketHeader::find($incoming);
            $ticket = TicketHeader::with(['details.user'])
                ->where('ticket_number', $incoming)
                ->first();

            if (!$ticket) {
                $response->message("❌ Ticket not found.");
                return response($response, 200)->header('Content-Type', 'text/xml');
            }

            $last = $ticket->details()
                ->where('user_id', '!=', $ticket->user_id) // only show updates from support agents
                ->latest('date_created')
                ->first();

            $msg = "🎫 Ticket #{$ticket->ticket_number}\n";
            $msg .= "Description: {$ticket->description}\n";
            $msg .= "Status: {$ticket->status}\n";
            $msg .= "Priority: {$ticket->priority}\n\n";
            $msg .= "📝 Last Update:\n";
            if ($last) {
                $msg .= $last->user->lname . " " . $last->user->fname . " (" . Carbon::parse($last->date_created)->format('Y-m-d H:i') . "): " . $last->message;
            } else {
                $msg .= "No updates yet from support.";
            }

            $response->message($msg);

            return response($response, 200)->header('Content-Type', 'text/xml');
        }

        // ===============================
        // DEFAULT MESSAGE
        // ===============================
        $response->message("👋 Click the system button to check your tickets.");

        return response($response, 200)->header('Content-Type', 'text/xml');
    }
}