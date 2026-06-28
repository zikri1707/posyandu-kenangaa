<?php

namespace App\View\Components\Layouts\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;

    public ?string $message;

    /**
     * Create a new component instance.
     */
    public function __construct($type = 'info', $message = null)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.ui.alert');
    }
}
