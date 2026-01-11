<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppApiController extends Controller
{
    /**
     * Send WhatsApp message via WA Gateway API
     */
    public function send(Request $request)
    {
        $phone = $request->input('phone');
        $message = $request->input('message');
        $imagePath = $request->input('image');

        if (empty($phone)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor WhatsApp tidak tersedia'
            ], 400);
        }

        if (empty($message)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesan tidak boleh kosong'
            ], 400);
        }

        try {
            // Send via WhatsApp API
            if (!empty($imagePath)) {
                $result = $this->sendWithImage($phone, $message, $imagePath);
            } else {
                $result = $this->sendMessage($phone, $message);
            }

            if ($result['success']) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pesan WhatsApp berhasil dikirim!'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send text message
     */
    protected function sendMessage(string $phone, string $message): array
    {
        $formattedPhone = $this->formatPhone($phone);

        $response = Http::timeout(30)->post(config('app.wa_api_url'), [
            'api_key' => config('app.wa_api_key'),
            'sender' => config('app.wa_sender'),
            'number' => $formattedPhone,
            'message' => $message
        ]);

        if ($response->successful()) {
            return ['success' => true, 'message' => 'Pesan terkirim'];
        }

        return [
            'success' => false,
            'message' => 'Gagal mengirim pesan: ' . $response->body()
        ];
    }

    /**
     * Send message with image
     */
    protected function sendWithImage(string $phone, string $message, string $imagePath): array
    {
        $formattedPhone = $this->formatPhone($phone);

        // Build full image URL
        $imageUrl = url('storage/' . $imagePath);

        $response = Http::timeout(30)->post(config('app.wa_api_url'), [
            'api_key' => config('app.wa_api_key'),
            'sender' => config('app.wa_sender'),
            'number' => $formattedPhone,
            'message' => $message,
            'imageUrl' => $imageUrl
        ]);

        if ($response->successful()) {
            return ['success' => true, 'message' => 'Pesan dengan gambar terkirim'];
        }

        return [
            'success' => false,
            'message' => 'Gagal mengirim pesan: ' . $response->body()
        ];
    }

    /**
     * Format phone number to Indonesian format
     */
    protected function formatPhone(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Convert 08 to 628
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        }

        // Add 62 if starts with 8
        if (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
