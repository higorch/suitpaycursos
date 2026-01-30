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

    public int $perPage = 15;
    public array $search = [];

    #[Title('Cursos')]
    public function render()
    {
        return view('livewire.panel.course.index', [
            'courses' => $this->courses
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
        return Course::query()->when(data_get($this->search, 'name'), function ($query, $term) {
            $query->where('name', 'like', "%{$term}%");
        })->when(data_get($this->search, 'creator'), function ($query, $creator) {
            $query->whereHas('creator', function ($query) use ($creator) {
                $query->where('ulid', $creator);
            });
        })->when(data_get($this->search, 'delivery_mode'), function ($query, $mode) {
            $query->where('delivery_mode', $mode);
        })->when(data_get($this->search, 'max_enrollments'), function ($query, $max) {
            $query->where('max_enrollments', $max);
        })->when(data_get($this->search, 'status'), function ($query, $status) {
            $query->where('status', $status);
        })->latest()->paginate($this->perPage);
    }
}
