<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrintServerStatusController extends Controller
{
    /**
     * Return the print server status based on heartbeat in database.
     */
    public function status()
    {
        $lastHeartbeat = DB::table('system_settings')
            ->where('setting_key', 'print_server_last_heartbeat')
            ->value('value');

        $printerName = DB::table('system_settings')
            ->where('setting_key', 'print_server_printer_name')
            ->value('value') ?? '-';

        $printerConnected = DB::table('system_settings')
            ->where('setting_key', 'print_server_printer_connected')
            ->value('value') === '1';

        $isOnline = false;
        if ($lastHeartbeat) {
            $diff = now()->diffInSeconds(Carbon::parse($lastHeartbeat));
            $isOnline = $diff < 30; // online if heartbeat within last 30 seconds
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'online' => $isOnline,
                'printer_connected' => $printerConnected,
                'printer_name' => $printerName,
                'last_heartbeat' => $lastHeartbeat,
            ]
        ]);
    }
}
