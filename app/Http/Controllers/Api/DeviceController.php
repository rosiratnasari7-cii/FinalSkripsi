<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Http\Controllers\Api\TelegramController;

class DeviceController extends Controller
{
    private static $relayStatus = 0; // default relay OFF

    // ======================
    // GET /api/relay
    // ======================
    public function getRelay()
    {
        return response()->json([
            'relay' => self::$relayStatus
        ]);
    }

    // ======================
    // POST /api/data
    // ======================
    public function storeData(Request $request)
    {
        $temperature = $request->input('temperature');
        $humidity    = $request->input('humidity');

        // simpan ke DB
        SensorData::create([
            'temperature' => $temperature,
            'humidity'    => $humidity
        ]);

        // kirim telegram
        TelegramController::notifyNewSensorData($temperature, $humidity);

        return response()->json([
            'message'     => 'Data received',
            'temperature' => $temperature,
            'humidity'    => $humidity
        ]);
    }

    // ======================
    // POST /api/relay
    // ======================
    public function updateRelay(Request $request)
    {
        $status = $request->input('status');

        // PASTIKAN relay hanya 0 atau 1
        self::$relayStatus = ($status == 1 ? 1 : 0);

        // Kirim notif telegram
        TelegramController::notifyRelayChange(self::$relayStatus);

        return response()->json([
            'relay' => self::$relayStatus
        ]);
    }

    // untuk telegram controller
    public static function updateRelayStatic($status)
    {
        self::$relayStatus = ($status == 1 ? 1 : 0);
    }

    public static function getRelayStatic()
    {
        return self::$relayStatus;
    }
}
