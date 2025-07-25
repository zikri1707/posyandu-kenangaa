<?php

// app/View/Components/Flux/Main.php

namespace App\View\Components\Flux;

use Illuminate\View\Component;

class Main extends Component
{
    public $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('components.flux.main');
    }
}
