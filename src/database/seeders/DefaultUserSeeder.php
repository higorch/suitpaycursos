<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'higorchristianfe@gmail.com',
                'name' => 'Higor Ferreira',
                'role' => 'admin'
            ],
            [
                'email' => 'maria@gmail.com',
                'name' => 'Maria Santos',
                'role' => 'teacher'
            ],
            [
                'email' => 'gustavo@gmail.com',
                'name' => 'Gustavo Silva',
                'role' => 'student'
            ],
        ];

        foreach ($users as $user) {
            $existingUser = User::where('email', $user['email'])->exists();

            // Cria o usuário se não existir
            if (!$existingUser) {
                User::create([
                    'role' => $user['role'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'password' => bcrypt('password'),
                    'at' => incrementIfExistDatabase(formatAt($user['name']), 'users', 'at'),
                    'status' => 'activated',
                ]);

                $this->command->info("Usuário {$user['name']} criado com sucesso!");
            }
        }
    }
}
