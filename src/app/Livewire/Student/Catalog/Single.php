<?php

namespace App\Livewire\Student\Catalog;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Scopes\CourseScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Single extends Component
{
    public string $at = '';
    public string $slug = '';

    public function render()
    {
        return view('livewire.student.catalog.single', [
            'course' => $this->course,
            'deliveryMode' => $this->deliveryMode,
        ])->title($this->pageTitle);
    }

    public function mount($at, $slug)
    {
        $this->at = $at;
        $this->slug = $slug;
    }

    #[On('action-confirm')]
    public function actionConfirm($context, $id)
    {
        if ($context === 'enroll-course') {
            $this->enrollCourse($id);
        }
    }

    #[Computed]
    public function pageTitle()
    {
        return $this->course?->name ?? 'Curso';
    }

    #[Computed]
    public function deliveryMode()
    {
        return match ($this->course->delivery_mode) {
            'online' => 'Online',
            'in-person' => 'Presencial',
            'hybrid' => 'Híbrido',
            default => null,
        };
    }

    #[Computed]
    public function course()
    {
        return Course::withoutGlobalScopes([
            CourseScope::class,
        ])->with(['teacher'])->withCount([
            'enrollments as enrolled_by_me_count' => function ($query) {
                $query->where('student_id', Auth::user()->id);
            }
        ])->whereHas('teacher', function ($query) {
            $query->where('at', $this->at);
        })->where('slug', $this->slug)->first();
    }

    public function enrollCourse($id)
    {
        $student = Auth::user();

        $course = Course::withoutGlobalScopes([CourseScope::class])->find($id);

        if (!$course) {
            $this->dispatch('notify', msg: 'Curso não encontrado.', type: 'error');
            return;
        }

        $alreadyEnrolled = Enrollment::where('course_id', $course->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadyEnrolled) {
            $this->dispatch('notify', msg: 'Você já está matriculado neste curso.', type: 'warning');
            return;
        }

        if ($course->status !== 'activated') {
            $this->dispatch('notify', msg: 'Este curso não está disponível para matrícula.', type: 'warning');
            return;
        }

        if ($course->enrollment_deadline && Carbon::parse($course->enrollment_deadline)->isPast()) {
            $this->dispatch('notify', msg: 'O prazo de matrícula já encerrou.', type: 'warning');
            return;
        }

        if ($course->max_enrollments) {
            $total = Enrollment::where('course_id', $course->id)->count();

            if ($total >= $course->max_enrollments) {
                $this->dispatch('notify', msg: 'Este curso já atingiu o limite de matrículas.', type: 'warning');
                return;
            }
        }

        // Gera número de matrícula único
        do {
            $registrationNumber = yearNumberRandon();
        } while (Enrollment::where('registration_number', $registrationNumber)->exists());

        Enrollment::create([
            'course_id'           => $course->id,
            'student_id'          => $student->id,
            'registration_number' => $registrationNumber,
            'status'              => 'active',
            'enrolled_at'         => now(),
        ]);

        session()->flash('success', 'Matrícula realizada com sucesso.');
    }
}
