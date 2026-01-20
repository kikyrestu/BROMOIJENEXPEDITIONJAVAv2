<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $seo;

    /**
     * Create a new component instance.
     */
    public function __construct($seo = null)
    {
        $this->seo = $seo;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
