<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendScheduleReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posyandu:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat WhatsApp otomatis untuk jadwal Posyandu besok (H-1)';

    /**
     * Execute the console command.
     */
    public function handle(ScheduleService $scheduleService): int
    {
        $this->info('Memulai pengiriman pengingat jadwal...');

        // Cari jadwal yang akan dilaksanakan besok
        $tomorrow = Carbon::tomorrow()->toDateString();
        $schedules = Schedule::whereDate('start_time', $tomorrow)
            ->whereNull('whatsapp_notif_sent_at') // Belum pernah dikirim otomatis
            ->get();

        if ($schedules->isEmpty()) {
            $this->info('Tidak ada jadwal untuk besok.');

            return Command::SUCCESS;
        }

        foreach ($schedules as $schedule) {
            $this->info("Mengirim pengingat untuk: {$schedule->title} (Posyandu ID: {$schedule->posyandu_id})");

            try {
                $count = $scheduleService->sendReminders($schedule);
                $this->info("Berhasil antrikan {$count} pesan.");
            } catch (\Exception $e) {
                Log::error('Gagal mengirim pengingat otomatis: '.$e->getMessage());
                $this->error('Gagal mengirim pengingat: '.$e->getMessage());
            }
        }

        $this->info('Pengiriman selesai.');

        return Command::SUCCESS;
    }
}
