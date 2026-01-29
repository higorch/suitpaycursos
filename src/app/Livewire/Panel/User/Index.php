<?php

namespace App\Livewire\Panel\User;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Usuários')]
    public function render()
    {
        return view('livewire.panel.user.index', [
            'users' => $this->users
        ]);
    }

    #[Computed]
    public function users()
    {
        return User::paginate(15);
    }
}
