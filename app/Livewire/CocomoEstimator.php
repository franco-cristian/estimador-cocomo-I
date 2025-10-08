<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // <--- Indicamos a Livewire que use nuestro nuevo layout
class CocomoEstimator extends Component
{
    public function render()
    {
        return view('livewire.cocomo-estimator');
    }
}