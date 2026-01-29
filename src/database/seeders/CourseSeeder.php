<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();

        if ($teachers->count() < 2) {
            $this->command->warn('É necessário pelo menos 2 professores. Rode o DefaultUserSeeder primeiro.');
            return;
        }

        $courses = [
            [
                'name' => 'Curso Gratuito de Laravel Livewire',
                'description' => 'Aprenda a construir interfaces dinâmicas e reativas no Laravel usando Livewire. Componentes, formulários reativos e comunicação entre partes da tela.',
                'video' => 'https://www.youtube.com/watch?v=lMNpKM3TbJw',
            ],
            [
                'name' => 'Curso Gratuito de TailwindCSS',
                'description' => 'Crie layouts modernos e responsivos com TailwindCSS, utilizando classes utilitárias para acelerar o desenvolvimento front-end.',
                'video' => 'https://www.youtube.com/watch?v=w4xS1ZdTcr8',
            ],
            [
                'name' => 'Curso de Alpine.js',
                'description' => 'Aprenda Alpine.js para adicionar interatividade ao HTML de forma leve e elegante, integrando facilmente com Laravel.',
                'video' => 'https://www.youtube.com/watch?v=rT3MAmX-jxI',
            ],
            [
                'name' => 'Curso de Laravel',
                'description' => 'Do básico ao avançado no Laravel: Eloquent, autenticação, filas, eventos e criação de APIs REST.',
                'video' => 'https://www.youtube.com/watch?v=SnOlhaJTMTA',
            ],
        ];

        foreach ($courses as $index => $course) {

            $slug = Str::slug($course['name']);

            if (Course::where('slug', $slug)->exists()) {
                continue;
            }

            // Alterna entre os professores (0,1,0,1...)
            $teacher = $teachers[$index % 2];

            Course::create([
                'teacher_id' => $teacher->id,
                'name' => $course['name'],
                'description' => $course['description'],
                'slug' => $slug,
                'presentation_video_url' => $course['video'],
                'status' => 'activated',
                'delivery_mode' => 'online',
                'max_enrollments' => rand(30, 70),
                'enrollment_deadline' => now()->addDays(rand(30, 60)),
            ]);

            $this->command->info("Curso {$course['name']} criado para o professor {$teacher->name}");
        }
    }
}
