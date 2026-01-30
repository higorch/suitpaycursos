<?php

use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('carrega o componente', function () {
    Livewire::test('panel.course.save')->assertStatus(200);
});

/*
|--------------------------------------------------------------------------
| Campos obrigatórios
|--------------------------------------------------------------------------
*/

it('nome é obrigatório', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', '')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.name' => 'required']);
});

it('descrição é obrigatória', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', '')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.description' => 'required']);
});

it('url do vídeo é obrigatória', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', '')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.presentation_video_url' => 'required']);
});

it('url do vídeo deve ser válida', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', 'url-invalida')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.presentation_video_url' => 'url']);
});

it('status deve ser válido', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'invalido')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.status']);
});

it('modalidade deve ser válida', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'invalido')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.delivery_mode']);
});

it('data limite é obrigatória', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', '')
        ->call('submit')
        ->assertHasErrors(['form.enrollment_deadline' => 'required']);
});

it('slug é obrigatório', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', '')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.slug' => 'required']);
});

it('slug deve estar no formato correto', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'Curso Inválido!')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->call('submit')
        ->assertHasErrors(['form.slug']);
});

it('max_enrollments deve ser maior que zero quando informado', function () {
    Livewire::test('panel.course.save')
        ->set('form.name', 'Curso Teste')
        ->set('form.description', 'Descrição válida')
        ->set('form.slug', 'curso-teste')
        ->set('form.presentation_video_url', 'https://example.com/video')
        ->set('form.status', 'activated')
        ->set('form.delivery_mode', 'online')
        ->set('form.enrollment_deadline', now()->addDays(5)->format('d/m/Y'))
        ->set('form.max_enrollments', 0)
        ->call('submit')
        ->assertHasErrors(['form.max_enrollments' => 'gt']);
});
