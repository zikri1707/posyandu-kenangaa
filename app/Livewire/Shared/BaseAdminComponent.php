<?php

namespace App\Livewire\Shared;

use App\Livewire\Traits\HasPosyanduScope;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Base class untuk seluruh komponen Livewire di area Admin.
 * Menggabungkan trait umum, layout, dan helper fungsionalitas.
 */
abstract class BaseAdminComponent extends Component
{
    use HasPosyanduScope, WithPagination;

    /**
     * Dispatch browser event untuk notifikasi (Toast).
     */
    protected function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('notify', [
            'message' => $message,
            'type' => $type,
        ]);

        session()->flash($type, $message);
    }

    /**
     * Helper untuk reset pagination saat filter berubah.
     */
    public function updatedSearch(): void
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }
}
