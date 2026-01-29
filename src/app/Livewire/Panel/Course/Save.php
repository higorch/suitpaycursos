<?php

namespace App\Livewire\Panel\Course;

use App\Livewire\Forms\CourseForm;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Save extends Component
{
    public CourseForm $form;

    public function render()
    {
        return view('livewire.panel.course.save', [
            'pageTitle' => $this->pageTitle
        ])->title($this->pageTitle);
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->form->edit($id);
        }
    }

    public function rendered()
    {
        $this->dispatch('errors-save-course', errors: $this->getErrorBag());

        $this->errorToastErrorBag();
        $this->resetErrorBag();
    }

    #[Computed]
    public function pageTitle()
    {
        return $this->form->isEditing() ? 'Editar Curso' : 'Cadastrar Curso';
    }

    public function submit()
    {
        $this->validate();

        try {
            $course = $this->form->save();

            session()->flash('success', 'Informações salvas com sucesso.');

            return $this->redirectRoute('panel.courses.edit', [
                'id' => $course->id
            ], navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('notify', msg: 'Não foi possível salvar.', type: 'error');
        }
    }

    private function errorToastErrorBag()
    {
        $errors = $this->getErrorBag();
        $count = count($errors->getMessages());

        if ($count > 0) {
            $this->dispatch('notify', msg: $count === 1 ? __('app.one_filling_problem') : $count . ' ' . __('app.filling_problems'), type: 'error');
        }
    }
}
