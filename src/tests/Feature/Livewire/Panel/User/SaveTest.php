<?php

use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('carrega o componente de usuário', function () {
    Livewire::test('panel.user.save')->assertStatus(200);
});

/*
|--------------------------------------------------------------------------
| Campos obrigatórios básicos
|--------------------------------------------------------------------------
*/

it('nome é obrigatório', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', '')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Senha@123')
        ->call('submit')
        ->assertHasErrors(['form.name' => 'required'])
        ->assertNoRedirect();
});

it('email é obrigatório', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', '')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Senha@123')
        ->call('submit')
        ->assertHasErrors(['form.email' => 'required'])
        ->assertNoRedirect();
});

it('email deve ser válido', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'email-invalido')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Senha@123')
        ->call('submit')
        ->assertHasErrors(['form.email' => 'email'])
        ->assertNoRedirect();
});

/*
|--------------------------------------------------------------------------
| Username (@)
|--------------------------------------------------------------------------
*/

it('username (at) é obrigatório', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', '')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Senha@123')
        ->call('submit')
        ->assertHasErrors(['form.at' => 'required'])
        ->assertNoRedirect();
});

it('username deve conter apenas letras minúsculas e números', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'Usuario@123')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Senha@123')
        ->call('submit')
        ->assertHasErrors(['form.at'])
        ->assertNoRedirect();
});

/*
|--------------------------------------------------------------------------
| Role e Status
|--------------------------------------------------------------------------
*/

it('role deve ser válido', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'invalido')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Senha@123')
        ->call('submit')
        ->assertHasErrors(['form.role'])
        ->assertNoRedirect();
});

it('status deve ser válido', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'invalido')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'Senha@123')
        ->call('submit')
        ->assertHasErrors(['form.status'])
        ->assertNoRedirect();
});

/*
|--------------------------------------------------------------------------
| Senha - criação
|--------------------------------------------------------------------------
*/

it('senha é obrigatória ao criar usuário', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', '')
        ->set('form.password_confirmation', '')
        ->call('submit')
        ->assertHasErrors([
            'form.password' => 'required',
            'form.password_confirmation' => 'required',
        ])
        ->assertNoRedirect();
});

it('senha deve ter pelo menos 8 caracteres', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Aa@1')
        ->set('form.password_confirmation', 'Aa@1')
        ->call('submit')
        ->assertHasErrors(['form.password'])
        ->assertNoRedirect();
});

it('senha deve conter letra minúscula', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'SENHA@123')
        ->set('form.password_confirmation', 'SENHA@123')
        ->call('submit')
        ->assertHasErrors(['form.password'])
        ->assertNoRedirect();
});

it('senha deve conter letra maiúscula', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'senha@123')
        ->set('form.password_confirmation', 'senha@123')
        ->call('submit')
        ->assertHasErrors(['form.password'])
        ->assertNoRedirect();
});

it('senha deve conter número', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha@abc')
        ->set('form.password_confirmation', 'Senha@abc')
        ->call('submit')
        ->assertHasErrors(['form.password'])
        ->assertNoRedirect();
});

it('senha deve conter caractere especial', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha123')
        ->set('form.password_confirmation', 'Senha123')
        ->call('submit')
        ->assertHasErrors(['form.password'])
        ->assertNoRedirect();
});

it('confirmação de senha deve ser igual à senha', function () {
    Livewire::test('panel.user.save')
        ->set('form.name', 'Usuário Teste')
        ->set('form.email', 'teste@email.com')
        ->set('form.role', 'student')
        ->set('form.status', 'activated')
        ->set('form.at', 'usuario123')
        ->set('form.password', 'Senha@123')
        ->set('form.password_confirmation', 'OutraSenha@123')
        ->call('submit')
        ->assertHasErrors(['form.password_confirmation' => 'same'])
        ->assertNoRedirect();
});
