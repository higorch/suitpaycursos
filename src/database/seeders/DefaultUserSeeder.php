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
                'role' => 'creator',
            ],
            [
                'email' => 'joao@mail.com',
                'name' => 'João Marcos',
                'role' => 'creator',
            ],
            [
                'email' => 'gustavo@mail.com',
                'name' => 'Gustavo Silva',
                'role' => 'student',
                'creator_email' => 'maria@mail.com',
            ],
            [
                'email' => 'danilo@mail.com',
                'name' => 'Danilo Canhoto',
                'role' => 'student',
                'creator_email' => 'joao@mail.com',
            ],
        ];

        foreach ($users as $data) {

            if (User::where('email', $data['email'])->exists()) {
                continue;
            }

            $creatorId = null;

            if (($data['role'] === 'student') && isset($data['creator_email'])) {
                $creator = User::where('email', $data['creator_email'])->first();
                $creatorId = $creator?->id;
            }

            User::create([
                'ulid' => Str::ulid(),
                'role' => $data['role'],
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'creator_id' => $creatorId,
                'at' => incrementIfExistDatabase(formatAt($data['name']), 'users', 'at'),
                'status' => 'activated',
            ]);

            $this->command->info("Usuário {$data['name']} criado.");
        }

        // USUÁRIOS DINÂMICOS (FAKER)
        $creators = User::where('role', 'creator')->get();

        // Já existem 5 usuários fixos, vamos criar mais 65
        User::factory()->count(65)->state(function () use ($creators) {

            $name = fake()->name();

            // Define papel aleatório (maior chance de ser aluno)
            $role = fake()->randomElement(['student', 'student', 'student', 'creator']);

            $creatorId = null;

            if ($role === 'student' && $creators->isNotEmpty()) {
                // 80% dos alunos terão criador
                $creatorId = fake()->boolean(80) ? $creators->random()->id : null;
            }

            return [
                'ulid' => Str::ulid(),
                'name' => $name,
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => $role,
                'creator_id' => $creatorId,
                'status' => 'activated',
                'at' => incrementIfExistDatabase(formatAt($name), 'users', 'at'),
            ];
        })->create();

        $this->command->info('Usuários fake criados com sucesso.');
    }
}
