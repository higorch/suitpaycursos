<?php

namespace App\Livewire\Panel\Course;

use App\Models\Course;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Title('Cursos')]
    public function render()
    {
        return view('livewire.panel.course.index', [
            'courses' => $this->courses
        ]);
    }

    #[On('action-delete')]
    public function confirmDelete($context, $id)
    {
        if ($context === 'course') {
            try {
                $course = Course::where('id', $id)->firstOrFail();

                // Exclusões em massa das relações
                $course->attachments()->delete();
                $course->enrollments()->delete();
                $course->thumbnail()->delete();

                // Agora remove o estudante
                $course->delete();

                $this->dispatch('notify', msg: "Excluído com sucesso!", type: "success");
            } catch (\Exception $e) {
                $this->dispatch('notify', msg: $e->getMessage(), type: "error");
            }
        }
    }

    #[Computed]
    public function courses()
    {
        return Course::paginate(1);
    }
}
