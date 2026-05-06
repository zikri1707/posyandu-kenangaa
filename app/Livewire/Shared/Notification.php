<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class Notification extends Component
{
    public $message;

    public $type;

    protected $rules = [
        'message' => 'required|string',
        'type' => 'required|string', // Can be 'success', 'error', 'info', etc.
    ];

    public function mount($message, $type)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function render()
    {
        return view('livewire.shared.notification');
    }
}
