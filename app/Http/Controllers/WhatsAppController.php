<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\TicketHeader;
use App\Models\TicketDetail;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function verify(Request $request)
    {
        $verifyToken = env('WHATSAPP_VERIFY_TOKEN');

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $request->get('hub_verify_token') === $verifyToken
        ) {
            return response($request->get('hub_challenge'), 200)
                ->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

    // public function webhook(Request $request)
    // {
    //     \Log::info('WhatsApp webhook received', $request->all());

    //     $data = $request->all();
    //     $entry = $data['entry'][0]['changes'][0]['value'] ?? null;

    //     if (!$entry || !isset($entry['messages'])) {
    //         \Log::info('No message in payload');
    //         return response()->json(['status' => 'no message']);
    //     }

    //     $msg = $entry['messages'][0];
    //     $from = $msg['from'];

    //     \Log::info('Message from: ' . $from, $msg);

    //     if (isset($msg['text'])) {
    //         $text = $msg['text']['body'];

    //         \Log::info('Text received: ' . $text);

    //         preg_match('/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/', $text, $matches);

    //         \Log::info('Email matches', $matches);

    //         if (!empty($matches)) {
    //             $this->handleEmail($from, $matches[0]);
    //             return response()->json(['status' => 'ok']);
    //         }

    //         $this->sendMessage($from, "👋 Send your email to view your open tickets.");
    //         return response()->json(['status' => 'ok']);
    //     }

    //     if (isset($msg['interactive'])) {
    //         $this->handleSelection($from, $msg);
    //         return response()->json(['status' => 'ok']);
    //     }

    //     return response()->json(['status' => 'ok']);
    // }


    public function webhook(Request $request)
    {
        // Log EVERYTHING that comes in
        \Log::info('========== WEBHOOK HIT ==========');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request headers: ', $request->headers->all());
        \Log::info('Request body: ', $request->all());
        \Log::info('Raw content: ' . $request->getContent());

        // Handle GET requests (verification)
        if ($request->method() === 'GET') {
            return $this->verify($request);
        }

        // Handle POST requests (messages)
        if ($request->method() === 'POST') {
            $data = $request->all();

            // Check for status updates
            if (isset($data['entry'][0]['changes'][0]['value']['statuses'])) {
                \Log::info('Status update received', $data['entry'][0]['changes'][0]['value']['statuses']);
                return response()->json(['status' => 'ok']);
            }

            $entry = $data['entry'][0]['changes'][0]['value'] ?? null;

            if (!$entry) {
                \Log::error('No entry found in webhook payload');
                return response()->json(['status' => 'no entry'], 200);
            }

            if (!isset($entry['messages'])) {
                \Log::info('No messages in payload', ['entry' => $entry]);
                return response()->json(['status' => 'no messages'], 200);
            }

            $msg = $entry['messages'][0];
            $from = $msg['from'];

            \Log::info('Message details', [
                'from' => $from,
                'type' => $msg['type'] ?? 'unknown',
                'message' => $msg
            ]);

            if (isset($msg['text'])) {
                $text = $msg['text']['body'];
                \Log::info('Text message received: ' . $text);

                // Try to extract email
                preg_match('/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/', $text, $matches);

                if (!empty($matches)) {
                    \Log::info('Email found: ' . $matches[0]);
                    $this->handleEmail($from, $matches[0]);
                } else {
                    \Log::info('No email found, sending prompt');
                    $this->sendMessage($from, "👋 Send your email to view your open tickets.");
                }
            } else {
                \Log::info('Non-text message received', ['type' => $msg['type'] ?? 'unknown']);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function handleEmail($from, $email)
    {
        \Log::info('handleEmail called', ['from' => $from, 'email' => $email]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            \Log::warning('User not found for email: ' . $email);
            $this->sendMessage($from, "❌ No account found for that email.");
            return;
        }

        \Log::info('User found: ' . $user->id);

        $user->update(['mobile_no' => $from]);

        $tickets = TicketHeader::with('details')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'Closed')
            ->orderBy('date_created', 'desc')   // ✅ avoid latest() since timestamps are off
            ->take(10)
            ->get();

        \Log::info('Tickets found: ' . $tickets->count());

        if ($tickets->isEmpty()) {
            $this->sendMessage($from, "✅ You have no open tickets at the moment.");
            return;
        }

        $this->sendTicketList($from, $tickets);
    }

    public function sendMessage($to, $message)
    {
        \Log::info('Sending message to: ' . $to, ['message' => $message]);

        $response = Http::withToken(env('WHATSAPP_TOKEN'))
            ->post("https://graph.facebook.com/v25.0/" . env('WHATSAPP_PHONE_ID') . "/messages", [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "text",
                "text" => [
                    "body" => $message
                ]
            ]);

        \Log::info('WhatsApp API response', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);
    }

    public function sendTicketList($to, $tickets)
    {
        $rows = [];

        foreach ($tickets as $t) {
            $rows[] = [
                "id" => (string) $t->id,
                "title" => "TCK-{$t->id}",
                "description" => $t->status
            ];
        }

        \Log::info('Sending ticket list', ['rows' => $rows]);

        $response = Http::withToken(env('WHATSAPP_TOKEN'))
            ->post("https://graph.facebook.com/v25.0/" . env('WHATSAPP_PHONE_ID') . "/messages", [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "interactive",
                "interactive" => [
                    "type" => "list",
                    "body" => [
                        "text" => "🎫 Your open tickets — tap one to view details:"
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

        \Log::info('WhatsApp list API response', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);
    }

    public function handleSelection($from, $msg)
    {
        $ticketId = $msg['interactive']['list_reply']['id'] ?? null;

        if (!$ticketId) {
            $this->sendMessage($from, "❌ Could not read your selection. Please try again.");
            return;
        }

        $ticket = TicketHeader::with('details')->find($ticketId);

        if (!$ticket) {
            $this->sendMessage($from, "❌ Ticket not found.");
            return;
        }

        $this->sendTicketDetails($from, $ticket);
    }

    public function sendTicketDetails($to, $ticket)
    {
        $lastDetail = $ticket->details()
            ->latest('date_created')
            ->first();

        $lastMessage = $lastDetail?->message ?? 'No updates yet.';
        $lastDate = $lastDetail?->date_created ?? 'N/A';

        $msg = "🎫 *Ticket Details*\n"
            . "No: TCK-{$ticket->id}\n"
            . "Description: {$ticket->description}\n"
            . "Status: {$ticket->status}\n"
            . "Priority: {$ticket->priority}\n"
            . "\n📝 *Last Update:*\n"
            . "{$lastMessage}\n"
            . "📅 {$lastDate}";

        $this->sendMessage($to, $msg);
    }
}