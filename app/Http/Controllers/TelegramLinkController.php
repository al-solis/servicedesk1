<?php

namespace App\Http\Controllers;

use App\Models\TelegramAccount;
use App\Models\TelegramLinkToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramLinkController extends Controller
{
    /**
     * Display Telegram integration page.
     */
    public function index()
    {
        $telegramAccount =
            auth()->user()->telegramAccount;

        return view(
            'profile.telegram',
            compact('telegramAccount')
        );
    }

    /**
     * Generate Telegram linking URL.
     */
    public function generate()
    {
        $user = auth()->user();

        /*
         * Delete previous unused tokens.
         */
        TelegramLinkToken::where(
            'user_id',
            $user->id
        )
            ->whereNull('used_at')
            ->delete();

        $token = Str::random(48);

        TelegramLinkToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(10),
        ]);

        $botUsername =
            config('services.telegram.bot_username');

        $url =
            'https://t.me/' .
            $botUsername .
            '?start=' .
            $token;

        return redirect()->away($url);
    }

    /**
     * Disconnect Telegram account.
     */
    public function disconnect()
    {
        $account =
            auth()->user()->telegramAccount;

        if ($account) {
            $account->update([
                'status' => 'Blocked',
            ]);
        }

        return back()->with(
            'success',
            'Telegram account disconnected successfully.'
        );
    }
}