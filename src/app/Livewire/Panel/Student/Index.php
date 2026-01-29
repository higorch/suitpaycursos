<?php

namespace App\Livewire\Panel\Student;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Title('Alunos')]
    public function render()
    {
        return view('livewire.panel.student.index', [
            'students' => $this->students
        ]);
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
        return User::where('role', 'student')->paginate(15);
    }
}
