<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaturanController extends Controller
{
    private $defaults = [
        'app_name' => 'Laporan Santri',
        'school_name' => 'Pondok Pesantren Mambaul Huda',
        'school_address' => '',
        'school_phone' => '',
        'wa_api_url' => 'http://serverwa.hello-inv.com/send-message',
        'wa_api_key' => '',
        'wa_sender' => '',
        'latitude' => '-7.2575',
        'longitude' => '112.7521',
        'radius_meters' => '100'
    ];

    public function index()
    {
        $settingsRaw = DB::table('settings')->pluck('value', 'key')->toArray();
        $settings = array_merge($this->defaults, $settingsRaw);

        return view('admin.pengaturan', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token']);

        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
