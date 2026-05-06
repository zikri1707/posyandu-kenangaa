<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengirim notifikasi WhatsApp menggunakan API Fonnte.
 * Dokumentasi: https://fonnte.com/api
 */
class WhatsAppService
{
    protected string $token;

    protected string $baseUrl;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->baseUrl = config('services.fonnte.base_url', 'https://api.fonnte.com');
    }

    /**
     * Kirim pesan WhatsApp ke satu nomor.
     *
     * @param  string  $target  Nomor tujuan (format: 08xx atau 628xx)
     * @param  string  $message  Isi pesan
     * @return array [success => bool, message => string]
     */
    public function sendMessage(string $target, string $message): array
    {
        if (empty($this->token)) {
            Log::warning('WhatsApp notification failed: FONNTE_TOKEN is not configured.');

            return ['success' => false, 'message' => 'Token Fonnte tidak dikonfigurasi.'];
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->asForm()->post($this->baseUrl.'/send', [
            'target' => $this->formatPhoneNumber($target),
            'message' => $message,
            'delay' => '2', // Delay in seconds to avoid spam detection
        ]);

        $data = $response->json();

        if ($response->successful() && ($data['status'] ?? false)) {
            return [
                'success' => true,
                'message' => 'Pesan berhasil dikirim.',
                'data' => $data,
            ];
        }

        Log::error('Fonnte WhatsApp API Error: '.($data['reason'] ?? 'Unknown error'));

        return [
            'success' => false,
            'message' => $data['reason'] ?? 'Gagal mengirim pesan WhatsApp.',
            'data' => $data,
        ];
    }

    /**
     * Kirim pesan ke banyak nomor sekaligus.
     *
     * @param  array  $targets  Daftar nomor tujuan
     * @param  string  $message  Isi pesan
     */
    public function sendBulk(array $targets, string $message): array
    {
        $formattedTargets = array_map([$this, 'formatPhoneNumber'], $targets);
        $targetString = implode(',', $formattedTargets);

        return $this->sendMessage($targetString, $message);
    }

    /**
     * Format nomor telepon ke standar 628xx.
     */
    protected function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62'.$phone;
        }

        return $phone;
    }
}
