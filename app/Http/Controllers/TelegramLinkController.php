<?php

namespace App\Http\Controllers;

use App\Models\TelegramAccount;
use App\Models\TelegramLinkToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TelegramLinkController extends Controller
{
    public function index()
    {
        $telegramAccount = auth()->user()->telegramAccount;

        return view('profile.telegram', compact('telegramAccount'));
    }

    public function generate()
    {
        $user = auth()->user();

        DB::transaction(function () use ($user, &$token) {
            // Invalidate any prior unused tokens
            TelegramLinkToken::where('user_id', $user->id)
                ->whereNull('used_at')
                ->delete();

            $token = Str::random(48);

            TelegramLinkToken::create([
                'user_id' => $user->id,
                'token' => $token,
                'expires_at' => now()->addMinutes(10),
            ]);
        });

        $botUsername = config('services.telegram.bot_username');

        return redirect()->away(
            'https://t.me/' . $botUsername . '?start=' . $token
        );
    }

    public function disconnect()
    {
        $user = auth()->user();

        DB::transaction(function () use ($user) {
            // 1. Mark the account as blocked (keep history)
            $account = TelegramAccount::where('user_id', $user->id)->first();

            if ($account) {
                $account->update(['status' => 'Blocked']);
            }

            // 2. Clear any pending link tokens (used or unused)
            TelegramLinkToken::where('user_id', $user->id)->delete();
        });

        return back()->with(
            'success',
            'Telegram account disconnected successfully.'
        );
    }
}