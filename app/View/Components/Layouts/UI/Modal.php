<?php

namespace App\View\Components\Layouts\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public string $id;

    public ?string $title;

    public string $size;

    /**
     * Create a new component instance.
     */
    public function __construct($id = 'modal', $title = null, $size = 'md')
    {
        $this->id = $id;
        $this->title = $title;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.ui.modal');
    }
}
