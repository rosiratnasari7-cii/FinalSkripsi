<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        \Log::info('TELEGRAM MASUK', $request->all());

        $textRaw = $request->message['text'] ?? '';
        $chatId  = $request->message['chat']['id'] ?? null;
        $token   = env('TELEGRAM_BOT_TOKEN');

        if (!$chatId || !$textRaw) {
            return response()->json(['ok' => true]);
        }

        $text = strtolower(trim(explode('@', $textRaw)[0]));

        // ===== RELAY ON =====
        if ($text === '/on') {
            Http::withHeaders([
                'X-API-KEY' => env('API_KEY')
            ])->post(url('/api/relay/update'), [
                'status' => 1
            ]);

            $this->send($token, $chatId, '✅ Relay ON');
        }

        // ===== RELAY OFF =====
        if ($text === '/off') {
            Http::withHeaders([
                'X-API-KEY' => env('API_KEY')
            ])->post(url('/api/relay/update'), [
                'status' => 0
            ]);

            $this->send($token, $chatId, '❌ Relay OFF');
        }

        return response()->json(['ok' => true]);
    }

    private function send($token, $chatId, $msg)
    {
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text'    => $msg
        ]);
    }
}
