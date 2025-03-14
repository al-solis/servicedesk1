<?php

namespace App\Http\Controllers;

use App\Services\DeepSeekService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    // // Show the chat interface
    // public function showChat()
    // {
    //     return view('ai.chat');
    // }

    // // Handle chatbot requests
    // public function chat(Request $request)
    // {
    //     $userMessage = $request->input('message');
    //     \Log::info('User message:', ['message' => $userMessage]);

    //     if (empty($userMessage)) {
    //         \Log::error('Empty message received');
    //         return response()->json(['error' => 'Message is required'], 400);
    //     }

    //     try {
    //         $response = app('App\Services\DeepSeekService')->sendMessage($userMessage);
    //         \Log::info('DeepSeek response:', ['response' => $response]);

    //         if ($response) {
    //             return response()->json(['response' => $response]);
    //         }

    //         \Log::error('Empty response from DeepSeek');
    //         return response()->json(['error' => 'Failed to get response from DeepSeek'], 500);
    //     } catch (\Exception $e) {
    //         \Log::error('Error in chat method:', ['error' => $e->getMessage()]);
    //         return response()->json(['error' => 'An error occurred. Please try again.'], 500);
    //     }
    // }

    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function showChat()
    {
        $user = Auth::user();
        return view('ai.chat', compact('user'));
    }

    public function chat(Request $request)
    {
        $user = Auth::user();
        $userMessage = $request->input('message');
        
        // Call Gemini API
        $response = $this->geminiService->sendMessage($userMessage);

        return response()->json(['response' => $response]);
    }
}
