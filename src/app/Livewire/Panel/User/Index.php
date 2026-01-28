<?php

namespace App\Livewire\Panel\User;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Usuários')]
    public function render()
    {
        return view('livewire.panel.user.index');
    }
}
