<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RelayController extends Controller
{
    
    public function view()
    {
        return view('relay');
    }

    public function status(Request $r)
    {
        if ($r->header('X-API-KEY') !== env('API_KEY_SECRET')) {
            abort(401, 'Unauthorized');
        }

        return response()->json([
            'status' => Cache::get('relay_status', 0)
        ]);
    }

    public function update(Request $r)
    {
        if ($r->header('X-API-KEY') !== env('API_KEY_SECRET')) {
            abort(401, 'Unauthorized');
        }

        $status = (int) $r->input('status', 0);

        // VALIDASI KETAT
        if (!in_array($status, [0, 1])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        Cache::put('relay_status', $status);

        $msg = $status == 1
            ? "🔌 Relay ON"
            : "⛔ Relay OFF";

        Http::get("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text'    => $msg
        ]);

        return response()->json([
            'success' => true,
            'status'  => $status
        ]);
    }

    // ======================
    // TELEGRAM WEBHOOK
    // ======================
    public function telegramWebhook(Request $r)
    {
        $text = strtolower(trim($r->input('message.text', '')));
        $chatId = $r->input('message.chat.id');

        if (!$chatId) return response()->json(['ok' => true]);

        if ($text === '/on') {
            Cache::put('relay_status', 1);
            $this->sendTelegram($chatId, '✅ Relay ON');
        }

        if ($text === '/off') {
            Cache::put('relay_status', 0);
            $this->sendTelegram($chatId, '❌ Relay OFF');
        }

        return response()->json(['ok' => true]);
    }

    
    private function sendTelegram($chatId, $msg)
    {
        Http::get("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
            'chat_id' => $chatId,
            'text'    => $msg
        ]);
    }
}
