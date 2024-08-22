<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    protected $apiKey;
    protected $baseUrl = 'https://wa-server.muhsalfazi.my.id/api/messages';

    public function __construct()
    {
        $this->apiKey = env('wa-key'); // Pastikan Anda sudah menambahkan API Key di .env
    }

    public function sendMessage($phone, $message)
    {
        $response = Http::post($this->baseUrl, [
            'apikey' => $this->apiKey,
            'phone' => $phone,
            'text' => $message,
        ]);

        return $response->json();
    }
}
