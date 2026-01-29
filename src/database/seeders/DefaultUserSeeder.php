<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        // USUÁRIOS FIXOS
        $users = [
            [
                'email' => 'suitpay@mail.com',
                'name' => 'Suitpay',
                'role' => 'admin',
            ],
            [
                'email' => 'maria@mail.com',
                'name' => 'Maria Santos',
                'role' => 'teacher',
            ],
            [
                'email' => 'joao@mail.com',
                'name' => 'João Marcos',
                'role' => 'teacher',
            ],
            [
                'email' => 'gustavo@mail.com',
                'name' => 'Gustavo Silva',
                'role' => 'student',
                'teacher_email' => 'maria@mail.com',
            ],
            [
                'email' => 'danilo@mail.com',
                'name' => 'Danilo Canhoto',
                'role' => 'student',
                'teacher_email' => 'joao@mail.com',
            ],
        ];

        foreach ($users as $data) {

            if (User::where('email', $data['email'])->exists()) {
                continue;
            }

            $teacherId = null;

            if (($data['role'] === 'student') && isset($data['teacher_email'])) {
                $teacher = User::where('email', $data['teacher_email'])->first();
                $teacherId = $teacher?->id;
            }

            User::create([
                'ulid' => Str::ulid(),
                'role' => $data['role'],
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'teacher_id' => $teacherId,
                'at' => incrementIfExistDatabase(formatAt($data['name']), 'users', 'at'),
                'status' => 'activated',
            ]);

            $this->command->info("Usuário {$data['name']} criado.");
        }

        // USUÁRIOS DINÂMICOS (FAKER)
        $teachers = User::where('role', 'teacher')->get();

        // Já existem 5 usuários fixos, vamos criar mais 65
        User::factory()->count(65)->state(function () use ($teachers) {

            $name = fake()->name();

            // Define papel aleatório (maior chance de ser aluno)
            $role = fake()->randomElement(['student', 'student', 'student', 'teacher']);

            $teacherId = null;

            if ($role === 'student' && $teachers->isNotEmpty()) {
                // 80% dos alunos terão professor
                $teacherId = fake()->boolean(80) ? $teachers->random()->id : null;
            }

            return [
                'ulid' => Str::ulid(),
                'name' => $name,
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => $role,
                'teacher_id' => $teacherId,
                'status' => 'activated',
                'at' => incrementIfExistDatabase(formatAt($name), 'users', 'at'),
            ];
        })->create();

        $this->command->info('Usuários fake criados com sucesso.');
    }
}
