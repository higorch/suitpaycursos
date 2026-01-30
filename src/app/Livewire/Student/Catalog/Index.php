<?php

namespace App\Livewire\Student\Catalog;

use App\Models\Course;
use App\Models\Scopes\CourseScope;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public int $perPage = 25;

    #[Title('Catalogo de Cursos')]
    public function render()
    {
        return view('livewire.student.catalog.index', [
            'courses' => $this->courses
        ]);
    }

    #[Computed]
    public function courses()
    {
        return Course::withoutGlobalScopes([
            CourseScope::class,
        ])->with([
            'creator'
        ])->where('status', 'activated')->latest()->paginate($this->perPage);
    }
}
