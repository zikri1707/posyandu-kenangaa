<?php

namespace App\Livewire\Shared;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->calculateUnread();
    }

    public function calculateUnread()
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $lastRead = $user->last_notifications_read_at;

        // If never read, show notifications from last 12 hours as baseline
        $query = ActivityLog::query();
        if ($lastRead) {
            $query->where('created_at', '>', $lastRead);
        } else {
            $query->where('created_at', '>', now()->subHours(12));
        }

        $this->unreadCount = $query->count();
    }

    public function markAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->update([
                'last_notifications_read_at' => now(),
            ]);
            $this->unreadCount = 0;
        }
    }

    public function render()
    {
        $recentLogs = ActivityLog::latest()->take(10)->get();

        // Use fresh() to ensure we get the updated timestamp after markAsRead
        $user = Auth::user()?->fresh();
        $lastRead = $user?->last_notifications_read_at ?? now()->subHours(12);

        $notifications = $recentLogs->map(function ($log) use ($lastRead) {
            $icon = match ($log->action_type) {
                'create' => 'fa-circle-plus',
                'update' => 'fa-pen-to-square',
                'delete' => 'fa-trash-can',
                default => 'fa-bell',
            };
            $color = match ($log->action_type) {
                'create' => 'bg-emerald-50 text-emerald-600',
                'update' => 'bg-blue-50 text-blue-600',
                'delete' => 'bg-red-50 text-red-600',
                default => 'bg-slate-50 text-slate-600',
            };

            $title = match ($log->action_type) {
                'create' => 'Data Baru',
                'update' => 'Pembaruan Data',
                'delete' => 'Penghapusan',
                default => 'Aktivitas',
            };

            $targetUrl = match (true) {
                str_contains($log->entity_type, 'Patient') && $log->action_type !== 'delete' => route('admin.patients.show', $log->entity_id),
                str_contains($log->entity_type, 'MedicalRecord') && $log->action_type !== 'delete' => route('admin.medical-records.show', $log->entity_id),
                str_contains($log->entity_type, 'Schedule') => route('admin.schedules.index'),
                str_contains($log->entity_type, 'Article') => route('admin.articles.index'),
                default => route('admin.activity-logs.index'),
            };

            return [
                'icon' => $icon,
                'color' => $color,
                'title' => $title,
                'desc' => $log->description,
                'time' => $log->created_at->diffForHumans(),
                'unread' => $log->created_at->gt($lastRead),
                'url' => $targetUrl,
            ];
        });

        return view('livewire.shared.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}
