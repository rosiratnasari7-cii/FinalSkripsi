<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\Relay;

class ApiController extends Controller
{
    // ===========================
    // GET DATA TERBARU UNTUK DASHBOARD
    // ===========================
    public function latestData()
    {
        $latest = SensorData::latest()->first();
        $relay = RelayStatus::latest()->first();

        return response()->json([
            'temperature' => $latest->temperature ?? 0,
            'humidity'    => $latest->humidity ?? 0,
            'voltage'     => $latest->voltage ?? 0,
            'current'     => $latest->current ?? 0,
            'power'       => $latest->power ?? 0,
            'relay'       => $relay->relay1 ?? 0,
        ]);
    }
}
