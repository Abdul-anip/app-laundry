<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * URL API Provider WhatsApp (Ganti dengan endpoint API Fonnte/Wablas/Watzap Anda)
     */
    protected string $endpoint;

    /**
     * API Token (Ambil dari dashboard Provider WhatsApp)
     */
    protected string $token;

    /**
     * Status simulasi (true = hanya log, false = kirim HTTP request beneran)
     */
    protected bool $isSimulationMode;

    public function __construct()
    {
        // Ganti credential ini nanti via .env (misal: env('FONNTE_TOKEN'))
        $this->endpoint = env('WHATSAPP_API_URL', 'https://api.fonnte.com/send');
        $this->token = env('WHATSAPP_API_TOKEN', 'YOUR_FONNTE_TOKEN');
        
        // Mode simulasi aktif by default jika token belum di-set di .env
        $this->isSimulationMode = env('WHATSAPP_SIMULATION_MODE', true);
    }

    /**
     * Format nomor HP agar sesuai dengan standar API (Awalan 62)
     */
    public function formatPhone(?string $phone): string
    {
        if (!$phone) return '';
        
        // Hapus semua karakter selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ganti awalan 0 menjadi 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        return $phone;
    }

    /**
     * Kirim pesan WhatsApp
     *
     * @param string $to Nomor tujuan
     * @param string $message Isi pesan text
     * @return bool Status pengiriman (berhasil/gagal)
     */
    public function sendMessage(string $to, string $message): bool
    {
        $formattedPhone = $this->formatPhone($to);

        if (empty($formattedPhone)) {
            Log::error('WhatsAppService: Nomor telepon kosong atau tidak valid.');
            return false;
        }

        // --- MOCKING / SIMULASI MODE ---
        if ($this->isSimulationMode) {
            Log::info("WhatsApp Simulasi (To: {$formattedPhone})", [
                'message' => $message
            ]);
            
            // Anggap berhasil untuk keperluan testing panel admin
            return true; 
        }

        // --- PRODUCTION MODE (Real HTTP Request) ---
        /*
        Contoh payload untuk Fonnte API:
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post($this->endpoint, [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62'
            ]);

            if ($response->successful() && isset($response['status']) && $response['status'] == true) {
                Log::info("WhatsApp terkirim ke {$formattedPhone}");
                return true;
            }

            Log::error("WhatsApp Gagal ke {$formattedPhone}: " . $response->body());
            return false;
            
        } catch (\Exception $e) {
            Log::error("WhatsApp API Exception: " . $e->getMessage());
            return false;
        }
        */

        // Jika Anda menggunakan provider selain Fonnte, ubah struktur form/payload request di atas.
        return true; 
    }
}
