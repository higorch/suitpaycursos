<?php

namespace App\Livewire\Student\Catalog;

use App\Models\Course;
use App\Models\Scopes\CourseScope;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Single extends Component
{
    public string $at = '';
    public string $slug = '';

    public function render()
    {
        return view('livewire.student.catalog.single', [
            'course' => $this->course
        ])->title($this->pageTitle);
    }

    public function mount($at, $slug)
    {
        $this->at = $at;
        $this->slug = $slug;
    }

    #[Computed]
    public function pageTitle()
    {
        return 'cadastrar Curso';
    }

    #[Computed]
    public function course()
    {
        return Course::withoutGlobalScopes([
            CourseScope::class,
        ])->with([
            'teacher'
        ])->whereHas('teacher', function ($query) {
            $query->where('at', $this->at);
        })->where('slug', $this->slug)->first();
    }
}
