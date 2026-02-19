<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SensorController extends Controller
{
    /**
     * Simpan data sensor dari ESP32
     */
    public function store(Request $r)
    {
        $this->checkApiKey($r);

        $data = $r->all();

        // ===============================
        // PEMBULATAN DATA SENSOR (2 DESIMAL)
        // ===============================
        $temperature = round(floatval($data['temperature'] ?? $data['temp'] ?? $data['suhu'] ?? 0), 2);
        $humidity    = round(floatval($data['humidity'] ?? $data['hum'] ?? $data['kelembapan'] ?? 0), 2);
        $voltage     = round(floatval($data['voltage'] ?? $data['tegangan'] ?? 0), 2);

        // ===============================
        // SIMPAN KE DATABASE
        // ===============================
        SensorData::create([
            'temperature' => $temperature,
            'humidity'    => $humidity,
            'tegangan'    => $voltage,
        ]);

        // ===============================
        // NOTIF TELEGRAM (ANTI SPAM)
        // ===============================
        $min = env('VOLTAGE_MIN', 198);
        $max = env('VOLTAGE_MAX', 242);
        $hysteresis = 1;

        $relay_status = Cache::get('relay_status', 0);
        $alertSent    = Cache::get('voltage_alert_sent', false);

        if (
            $relay_status == 1 &&
            $voltage > 50 &&
            ($voltage < ($min - $hysteresis) || $voltage > ($max + $hysteresis))
        ) {
            if ($alertSent === false) {
                $this->sendTelegramNotification($voltage);
                Cache::put('voltage_alert_sent', true);
            }
        } else {
            Cache::forget('voltage_alert_sent');
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Ambil data sensor terbaru
     */
    public function latest(Request $r)
    {
        $this->checkApiKey($r);

        $latest = SensorData::latest()->first();

        return response()->json([
            'temperature' => round($latest->temperature ?? 0, 2),
            'humidity'    => round($latest->humidity ?? 0, 2),
            'voltage'     => round($latest->tegangan ?? 0, 2),
            'status'      => Cache::get('relay_status', 0),
        ]);
    }

    /**
     * ===============================
     * HISTORI SENSOR
     * ===============================
     */
    public function histori()
    {
        $data = SensorData::orderBy('created_at', 'desc')->get();
        return view('histori', compact('data'));
    }

    /**
     * Cek API Key
     */
    private function checkApiKey(Request $r)
    {
        if ($r->header('X-API-KEY') !== env('API_KEY_SECRET')) {
            abort(401, 'Unauthorized');
        }
    }

    /**
     * Kirim notif ke Telegram
     */
    private function sendTelegramNotification($voltage)
    {
        $token   = env('TELEGRAM_BOT_TOKEN');
        $chat_id = env('TELEGRAM_CHAT_ID');

        $msg  = "⚠ Tegangan abnormal terdeteksi: {$voltage} V\n";
        $msg .= "Batas normal: "
            .env('VOLTAGE_MIN',198)
            ."V - "
            .env('VOLTAGE_MAX',242)
            ."V";

        Http::get("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chat_id,
            'text'    => $msg,
        ]);
    }
}
