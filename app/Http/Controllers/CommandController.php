<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandController extends Controller
{
    public function get()
    {
        $cmd = DB::table('command')->where('id', 1)->first();

        return response()->json([
            'command' => $cmd->command
        ]);
    }

    public function update(Request $request)
    {
        DB::table('command')->where('id', 1)->update([
            'command' => $request->command
        ]);

        return response()->json(['message' => 'OK']);
    }
}
