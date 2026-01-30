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

    public int $perPage = 15;
    public array $search = [];

    #[Title('Usuários')]
    public function render()
    {
        return view('livewire.panel.user.index', [
            'users' => $this->users
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->setPage($this->getPage());
    }

    #[On('set-filter-fields')]
    public function setFilterFields($fields)
    {
        $this->search = $fields;
        $this->resetPage();
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
        return User::query()->when(data_get($this->search, 'name'), function ($query, $term) {
            $query->where('name', 'like', "%{$term}%");
        })->when(data_get($this->search, 'email'), function ($query, $term) {
            $query->where('email', 'like', "%{$term}%");
        })->when(data_get($this->search, 'cpf_cnpj'), function ($query, $term) {
            $query->where('cpf_cnpj', sanitizeSpecialCharacters($term, true));
        })->when(data_get($this->search, 'status'), function ($query, $term) {
            $query->where('status', $term);
        })->when(data_get($this->search, 'role'), function ($query, $term) {
            $query->where('role', $term);
        })->latest()->paginate($this->perPage);
    }
}
