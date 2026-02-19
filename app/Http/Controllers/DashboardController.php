<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Http\Controllers\Api\DeviceController;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // =========================
    // HALAMAN DASHBOARD UTAMA
    // =========================
     public function index()
    {
        $latest = SensorData::latest()->first();

        return view('dashboard', [
            'title' => 'Dashboard',
            'data'  => $latest
        ]);
    }

    // JSON: data terbaru untuk card / tabel singkat (dipanggil AJAX)
    public function latestData()
    {
        $latest = SensorData::latest()->first();
        $relay = DeviceController::getRelayStatic();

        return response()->json([
            'latest' => $latest,
            'relay'  => $relay
        ]);
    }

    // JSON: data untuk chart (ambil 20 data terakhir, ascending)
    public function chartData()
    {
        $rows = SensorData::orderBy('id','desc')->take(20)->get()->reverse()->values();
        return response()->json($rows);
    }

    // POST: toggle relay dari dashboard (form atau AJAX)
    public function toggleRelay(Request $request)
    {
        $status = (int) $request->input('relay', 0);

        // update static var di DeviceController (sudah ada static methods)
        DeviceController::updateRelayStatic($status);

        // optional: kirim notifikasi telegram via TelegramController jika ada
        // \App\Http\Controllers\Api\TelegramController::notifyRelayChange($status);

        if ($request->wantsJson()) {
            return response()->json(['relay' => $status]);
        }

        return redirect()->route('dashboard')->with('success', 'Relay updated');
    }
}
