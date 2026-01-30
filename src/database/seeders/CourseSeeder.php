<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Criadores
        $creators = User::where('role', 'creator')->take(2)->get();

        if ($creators->count() < 2) {
            $this->command->warn('É necessário pelo menos 2 criadores. Rode o DefaultUserSeeder primeiro.');
            return;
        }

        // Alunos vinculados aos criadores
        $studentsForCreators = [
            $creators[0]->id => [
                ['name' => 'Lucas Andrade', 'email' => 'lucas.andrade@mail.com'],
                ['name' => 'Fernanda Lima', 'email' => 'fernanda.lima@mail.com'],
            ],
            $creators[1]->id => [
                ['name' => 'Rafael Souza', 'email' => 'rafael.souza@mail.com'],
                ['name' => 'Camila Rocha', 'email' => 'camila.rocha@mail.com'],
            ],
        ];

        foreach ($studentsForCreators as $creatorId => $students) {
            foreach ($students as $student) {
                User::firstOrCreate(
                    ['email' => $student['email']],
                    [
                        'ulid' => Str::ulid(),
                        'name' => $student['name'],
                        'password' => Hash::make('123456'),
                        'role' => 'student',
                        'status' => 'activated',
                        'creator_id' => $creatorId,
                        'at' => incrementIfExistDatabase(
                            formatAt($student['name']),
                            'users',
                            'at'
                        ),
                    ]
                );
            }
        }

        // Alunos sem criador
        $independentStudents = [
            ['name' => 'Juliana Martins', 'email' => 'juliana.martins@mail.com'],
            ['name' => 'Bruno Almeida', 'email' => 'bruno.almeida@mail.com'],
        ];

        foreach ($independentStudents as $student) {
            User::firstOrCreate(
                ['email' => $student['email']],
                [
                    'ulid' => Str::ulid(),
                    'name' => $student['name'],
                    'password' => Hash::make('123456'),
                    'role' => 'student',
                    'status' => 'activated',
                    'creator_id' => null,
                    'at' => incrementIfExistDatabase(
                        formatAt($student['name']),
                        'users',
                        'at'
                    ),
                ]
            );
        }

        $this->command->info('Alunos criados com sucesso.');

        // Cursos
        $courses = [
            [
                'name' => 'Curso Gratuito de Laravel Livewire',
                'description' => 'Aprenda a construir interfaces dinâmicas e reativas no Laravel usando Livewire.',
                'video' => 'https://www.youtube.com/watch?v=lMNpKM3TbJw',
                'delivery_mode' => 'online',
            ],
            [
                'name' => 'Curso Gratuito de TailwindCSS',
                'description' => 'Crie layouts modernos e responsivos com TailwindCSS.',
                'video' => 'https://www.youtube.com/watch?v=w4xS1ZdTcr8',
                'delivery_mode' => 'online',
            ],
            [
                'name' => 'Curso de Alpine.js',
                'description' => 'Aprenda Alpine.js para adicionar interatividade ao HTML.',
                'video' => 'https://www.youtube.com/watch?v=rT3MAmX-jxI',
                'delivery_mode' => 'hybrid',
            ],
            [
                'name' => 'Curso de Laravel',
                'description' => 'Do básico ao avançado no Laravel.',
                'video' => 'https://www.youtube.com/watch?v=SnOlhaJTMTA',
                'delivery_mode' => 'in-person',
            ],
        ];

        foreach ($courses as $index => $course) {

            $slug = Str::slug($course['name']);

            if (Course::where('slug', $slug)->exists()) {
                continue;
            }

            $creator = $creators[$index % 2];

            Course::create([
                'creator_id' => $creator->id,
                'name' => $course['name'],
                'description' => $course['description'],
                'slug' => $slug,
                'presentation_video_url' => $course['video'],
                'status' => 'activated',
                'delivery_mode' => $course['delivery_mode'],
                'max_enrollments' => rand(30, 70),
                'enrollment_deadline' => now()->addDays(rand(30, 60)),
            ]);

            $this->command->info("Curso {$course['name']} criado para {$creator->name}");
        }
    }
}
