<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\TicketHeader;

class WhatsAppController extends Controller
{
    // 🔐 Webhook verification (Meta requirement)
    public function verify(Request $request)
    {
        if ($request->hub_verify_token == env('WHATSAPP_VERIFY_TOKEN')) {
            return response($request->hub_challenge, 200);
        }

        return response('Invalid token', 403);
    }

    // 📩 Main webhook handler
    public function webhook(Request $request)
    {
        $data = $request->all();

        $entry = $data['entry'][0]['changes'][0]['value'] ?? null;

        if (!$entry || !isset($entry['messages'])) {
            return response()->json(['status' => 'no message']);
        }

        $msg = $entry['messages'][0];
        $from = $msg['from'];

        // TEXT MESSAGE
        if (isset($msg['text'])) {
            $text = $msg['text']['body'];

            // 🔍 Extract email from message
            preg_match('/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/', $text, $matches);

            if (!empty($matches)) {
                return $this->handleEmail($from, $matches[0]);
            }

            return $this->sendMessage($from, "👋 Send your email to view tickets.");
        }

        // 📋 Handle interactive list selection
        if (isset($msg['interactive'])) {
            return $this->handleSelection($from, $msg);
        }

        return response()->json(['status' => 'ok']);
    }


    public function handleEmail($from, $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->sendMessage($from, "❌ Email not found.");
        }

        // Save WhatsApp number
        $user->update(['mobile_no' => $from]);

        $tickets = TicketHeader::with('details')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'Closed')
            ->latest('date_created')
            ->take(10)
            ->get();

        if ($tickets->isEmpty()) {
            return $this->sendMessage($from, "✅ No open tickets.");
        }

        return $this->sendTicketList($from, $tickets);
    }

    // 📋 Send interactive ticket list
    public function sendTicketList($to, $tickets)
    {
        $rows = [];

        foreach ($tickets as $t) {
            $rows[] = [
                "id" => (string) $t->id,
                "title" => $t->ticket_no,
                "description" => $t->status
            ];
        }

        Http::withToken(env('WHATSAPP_TOKEN'))
            ->post("https://graph.facebook.com/v19.0/" . env('WHATSAPP_PHONE_ID') . "/messages", [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "interactive",
                "interactive" => [
                    "type" => "list",
                    "body" => [
                        "text" => "🎫 Select your ticket"
                    ],
                    "action" => [
                        "button" => "View Tickets",
                        "sections" => [
                            [
                                "title" => "Open Tickets",
                                "rows" => $rows
                            ]
                        ]
                    ]
                ]
            ]);
    }

    // 🧠 Handle ticket selection
    public function handleSelection($from, $msg)
    {
        $ticketId = $msg['interactive']['list_reply']['id'];

        $ticket = TicketHeader::find($ticketId);

        if (!$ticket) {
            return $this->sendMessage($from, "❌ Ticket not found.");
        }

        return $this->sendTicketDetails($from, $ticket);
    }

    // 🔍 Send ticket details
    public function sendTicketDetails($to, $ticket)
    {
        // Get latest activity from TicketDetail
        $lastDetail = $ticket->details()
            ->latest('date_created')
            ->first();

        $lastMessage = $lastDetail ? $lastDetail->message : 'No updates yet';
        $lastDate = $lastDetail ? $lastDetail->date_created : 'N/A';

        $msg = "🎫 Ticket Details\n"
            . "No: TCK-{$ticket->id}\n"
            . "Description: {$ticket->description}\n"
            . "Status: {$ticket->status}\n"
            . "Priority: {$ticket->priority}\n"
            . "\n📝 Last Update:\n"
            . "{$lastMessage}\n"
            . "📅 {$lastDate}";

        return $this->sendMessage($to, $msg);
    }

    // 📤 Send text message
    public function sendMessage($to, $message)
    {
        Http::withToken(env('WHATSAPP_TOKEN'))
            ->post("https://graph.facebook.com/v19.0/" . env('WHATSAPP_PHONE_ID') . "/messages", [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "text",
                "text" => [
                    "body" => $message
                ]
            ]);
    }
}