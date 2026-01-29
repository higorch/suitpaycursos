<?php

namespace App\Livewire\Forms;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Form;

class CourseForm extends Form
{
    public ?string $id = null;
    public ?int $teacher_id = null;
    public string $name = '';
    public string $description = '';
    public string $slug = '';
    public string $presentation_video_url = '';
    public string $status = 'activated';
    public string $delivery_mode = '';
    public ?int $max_enrollments = null;
    public ?string $enrollment_deadline = null;

    public function isEditing(): bool
    {
        return filled($this->id);
    }

    public function edit(string $id): void
    {
        $course = $this->getCourse($id);
        if (!$course) return;

        $this->id = $course->id;
        $this->teacher_id = $course->teacher_id;
        $this->name = $course->name;
        $this->description = $course->description;
        $this->slug = $course->slug;
        $this->presentation_video_url = $course->presentation_video_url;
        $this->status = $course->status;
        $this->delivery_mode = $course->delivery_mode;
        $this->max_enrollments = $course->max_enrollments;
        $this->enrollment_deadline = $course->enrollment_deadline ? Carbon::parse($course->enrollment_deadline)->format('d/m/Y') : null;
    }

    public function save(): ?Course
    {
        return $this->isEditing() ? $this->update() : $this->create();
    }

    protected function create(): ?Course
    {
        return Course::create($this->getCourseData());
    }

    protected function update(): ?Course
    {
        $course = $this->getCourse($this->id);
        if (!$course) return null;

        $course->update($this->getCourseData());
        return $course;
    }

    protected function getCourseData(): array
    {
        $data = [
            'teacher_id' => $this->teacher_id ?? auth()->id(),
            'name' => $this->name,
            'description' => $this->description,
            'slug' => rtrim(Str::slug($this->slug), '-'),
            'presentation_video_url' => $this->presentation_video_url,
            'status' => $this->status,
            'delivery_mode' => $this->delivery_mode,
            'max_enrollments' => $this->max_enrollments,
            'enrollment_deadline' => $this->enrollment_deadline ? Carbon::createFromFormat('d/m/Y', $this->enrollment_deadline)->format('Y-m-d')  : null,
        ];

        return $data;
    }

    private function getCourse(string $id): ?Course
    {
        return Course::where('id', $id)->first();
    }

    protected function prepareForValidation($attributes): array
    {
        if (!empty($attributes['enrollment_deadline'])) {
            $attributes['enrollment_deadline'] = Carbon::createFromFormat('d/m/Y',  $attributes['enrollment_deadline'])->format('Y-m-d');
        }

        return $attributes;
    }

    protected function rules(): array
    {
        $rules = [
            'name' => ['required'],
            'description' => ['required'],
            'presentation_video_url' => ['required', 'url'],
            'status' => ['required', 'in:activated,disabled'],
            'delivery_mode' => ['required', 'in:online,in-person,hybrid'],
            'max_enrollments' => ['nullable', 'integer'],
            'enrollment_deadline' => ['required', 'date'],
            'slug' => [
                'required',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'
            ],
        ];

        return $rules;
    }
}
