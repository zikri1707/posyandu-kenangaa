<?php

namespace App\View\Components\Layouts\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataCard extends Component
{
    public ?string $title;

    public ?string $icon;

    public string $variant;

    /**
     * Create a new component instance.
     */
    public function __construct($title = null, $icon = null, $variant = 'default')
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->variant = $variant;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.ui.datacard');
    }
}
