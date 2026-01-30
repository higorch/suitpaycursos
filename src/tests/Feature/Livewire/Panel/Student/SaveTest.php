<?php

use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

it('carrega o componente', function () {
    Livewire::test('panel.student.save')->assertStatus(200);
});

/*
|--------------------------------------------------------------------------
| Campos obrigatórios
|--------------------------------------------------------------------------
*/

it('nome é obrigatório', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', '')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->call('submit')
        ->assertHasErrors(['form.name' => 'required']);
});

it('email é obrigatório', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', '')
        ->set('form.at', 'usuario1234')
        ->call('submit')
        ->assertHasErrors(['form.email' => 'required']);
});

it('email deve ser válido', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'email-invalido')
        ->set('form.at', 'usuario1234')
        ->call('submit')
        ->assertHasErrors(['form.email' => 'email']);
});

it('username (at) é obrigatório', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', '')
        ->call('submit')
        ->assertHasErrors(['form.at' => 'required']);
});

it('username (at) deve conter apenas letras e números minúsculos', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'Usuario-Invalido!')
        ->call('submit')
        ->assertHasErrors(['form.at']);
});

/*
|--------------------------------------------------------------------------
| Validações de unicidade
|--------------------------------------------------------------------------
*/

it('username não pode ser duplicado', function () {
    User::factory()->create([
        'name' => 'Usuário Existente',
        'email' => 'existente@email.com',
        'at' => 'usuario1234',
        'status' => 'activated',
        'role' => 'student',
        'password' => bcrypt('Senha@123'),
    ]);

    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'novo@email.com')
        ->set('form.at', 'usuario1234')
        ->call('submit')
        ->assertHasErrors(['form.at' => 'unique']);
});

it('email não pode ser duplicado', function () {
    User::factory()->create([
        'name' => 'Usuário Existente',
        'email' => 'teste@email.com',
        'at' => 'usuarioexistente',
        'status' => 'activated',
        'role' => 'student',
        'password' => bcrypt('Senha@123'),
    ]);

    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->call('submit')
        ->assertHasErrors(['form.email' => 'unique']);
});

/*
|--------------------------------------------------------------------------
| Senha
|--------------------------------------------------------------------------
*/

it('senha deve ter no mínimo 8 caracteres quando informada', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->set('form.password', 'Ab1@')
        ->set('form.password_confirmation', 'Ab1@')
        ->call('submit')
        ->assertHasErrors(['form.password' => 'min']);
});

it('senha deve conter letra minúscula', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->set('form.password', 'SENHA123@')
        ->set('form.password_confirmation', 'SENHA123@')
        ->call('submit')
        ->assertHasErrors(['form.password']);
});

it('senha deve conter letra maiúscula', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->set('form.password', 'senha123@')
        ->set('form.password_confirmation', 'senha123@')
        ->call('submit')
        ->assertHasErrors(['form.password']);
});

it('senha deve conter número', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->set('form.password', 'Senha@@@')
        ->set('form.password_confirmation', 'Senha@@@')
        ->call('submit')
        ->assertHasErrors(['form.password']);
});

it('senha deve conter caractere especial', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->set('form.password', 'Senha1234')
        ->set('form.password_confirmation', 'Senha1234')
        ->call('submit')
        ->assertHasErrors(['form.password']);
});

it('confirmação de senha deve ser igual', function () {
    Livewire::test('panel.student.save')
        ->set('form.name', 'Aluno Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.at', 'usuario1234')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Outra@123')
        ->call('submit')
        ->assertHasErrors(['form.password_confirmation' => 'same']);
});
