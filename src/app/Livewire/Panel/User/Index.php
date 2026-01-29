<?php

namespace App\Livewire\Panel\User;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Title('Usuários')]
    public function render()
    {
        return view('livewire.panel.user.index', [
            'users' => $this->users
        ]);
    }

    #[On('action-delete')]
    public function confirmDelete($context, $id)
    {
        if ($context === 'user') {
            try {
                $user = User::where('ulid', $id)->firstOrFail();

                // Exclusões em massa das relações
                $user->attachments()->delete();
                $user->enrollments()->delete();
                $user->courses()->delete();
                $user->avatar()->delete();

                // Agora remove o usuário
                $user->delete();

                $this->dispatch('notify', msg: "Excluído com sucesso!", type: "success");
            } catch (\Exception $e) {
                $this->dispatch('notify', msg: $e->getMessage(), type: "error");
            }
        }
    }

    #[Computed]
    public function users()
    {
        return User::paginate(15);
    }
}
