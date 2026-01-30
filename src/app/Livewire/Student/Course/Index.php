<?php

namespace App\Livewire\Student\Course;

use App\Models\Course;
use App\Models\Scopes\CourseScope;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title("Meus Cursos")]
    public function render()
    {
        return view('livewire.student.course.index', [
            'courses' => $this->courses
        ]);
    }

    #[Computed]
    public function courses()
    {
        return Course::withoutGlobalScopes([
            CourseScope::class,
        ])->whereHas('enrollments', function ($query) {
            $query->where('student_id', Auth::id());
        })->with(['creator', 'thumbnail'])->latest()->paginate(12);
    }
}
