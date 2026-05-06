<?php

namespace App\Services;

use App\Jobs\SendWhatsAppNotificationJob;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Carbon;

class ScheduleService
{
    /**
     * Create a new schedule.
     */
    public function createSchedule(array $data, User $user): Schedule
    {
        if (! $user->isSuperAdmin()) {
            $data['posyandu_id'] = $user->posyandu_id;
        }

        $data['status'] = $data['status'] ?? 'upcoming';
        $data['user_id'] = $user->id;

        return Schedule::create($data);
    }

    /**
     * Update an existing schedule.
     */
    public function updateSchedule(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);

        return $schedule;
    }

    /**
     * Delete a schedule.
     */
    public function deleteSchedule(Schedule $schedule): void
    {
        $schedule->delete();
    }

    /**
     * Kirim pengingat WhatsApp untuk jadwal tertentu.
     * Digunakan oleh scheduler otomatis (H-1).
     */
    public function sendReminders(Schedule $schedule): int
    {
        $count = 0;
        Patient::where('posyandu_id', $schedule->posyandu_id)
            ->whereNotNull('phone_number')
            ->chunkById(100, function ($patients) use ($schedule, &$count) {
                foreach ($patients as $patient) {
                    $message = "Halo Ibu/Bapak {$patient->full_name}, jangan lupa besok ada kegiatan di Posyandu: *{$schedule->title}*.\n\n".
                              '📅 Tanggal: '.Carbon::parse($schedule->start_time)->translatedFormat('l, d F Y')."\n".
                              "📍 Lokasi: {$schedule->location}\n".
                              '⏰ Waktu: '.Carbon::parse($schedule->start_time)->format('H:i')." WIB\n\n".
                              'Mohon kehadirannya. Terima kasih.';

                    SendWhatsAppNotificationJob::dispatch($patient->phone_number, $message, $schedule->id);
                    $count++;
                }
            });

        $schedule->update([
            'whatsapp_notif_sent_at' => now(),
            'whatsapp_notif_count' => $count,
        ]);

        return $count;
    }

    /**
     * Kirim notifikasi manual dari dashboard.
     */
    public function sendManualNotification(Schedule $schedule): int
    {
        // Sama dengan sendReminders tapi mungkin dengan pesan yang berbeda
        return $this->sendReminders($schedule);
    }
}
