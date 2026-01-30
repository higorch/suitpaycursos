<?php

namespace App\Livewire\Panel\Student;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

    #[Title('Alunos')]
    public function render()
    {
        return view('livewire.panel.student.index', [
            'students' => $this->students
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
        if ($context === 'student') {
            try {
                $student = User::where('ulid', $id)->firstOrFail();

                // Exclusões em massa das relações
                $student->attachments()->delete();
                $student->enrollments()->delete();
                $student->avatar()->delete();

                // Agora remove o estudante
                $student->delete();

                $this->dispatch('notify', msg: "Excluído com sucesso!", type: "success");
            } catch (\Exception $e) {
                $this->dispatch('notify', msg: $e->getMessage(), type: "error");
            }
        }
    }

    #[Computed]
    public function students()
    {
        $user = Auth::user();

        $query = User::query()->where('role', 'student')->when(data_get($this->search, 'name'), function ($query, $term) {
            $query->where('name', 'like', "%{$term}%");
        })->when(data_get($this->search, 'email'), function ($query, $term) {
            $query->where('email', 'like', "%{$term}%");
        })->when(data_get($this->search, 'cpf_cnpj'), function ($query, $term) {
            $query->where('cpf_cnpj', 'like', "%{$term}%");
        })->when(data_get($this->search, 'status'), function ($query, $term) {
            $query->where('status', $term);
        });

        if ($user->role === 'admin') return $query->latest()->paginate($this->perPage);

        return $query->where(function ($query) use ($user) {
            $query->where('creator_id', $user->id)
                ->orWhereHas('enrollments.course', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                });
        })->distinct()->latest()->paginate($this->perPage);
    }
}
