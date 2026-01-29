<?php

namespace App\Livewire\Panel\User;

use App\Livewire\Forms\UserForm;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Save extends Component
{
    public UserForm $form;

    public function render()
    {
        return view('livewire.panel.user.save', [
            'pageTitle' => $this->pageTitle
        ])->title($this->pageTitle);
    }

    public function mount($ulid = null)
    {
        if ($ulid) {
            $this->form->edit($ulid);
        }
    }

    public function rendered()
    {
        $this->dispatch('errors-save-user', errors: $this->getErrorBag());

        $this->errorToastErrorBag();
        $this->resetErrorBag();
    }

    #[Computed]
    public function pageTitle()
    {
        return $this->form->isEditing() ? 'Editar Usuário' : 'Cadastrar Usuário';
    }

    public function submit()
    {
        $this->validate();

        try {
            $user = $this->form->save();

            session()->flash('success', 'Informações salvas com sucesso.');

            return $this->redirectRoute('panel.users.edit', [
                'ulid' => $user->ulid
            ], navigate: true);
        } catch (\Exception $e) {

            dd($e);
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
