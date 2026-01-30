<?php

namespace App\Livewire\Panel\Profile;

use App\Livewire\Forms\ProfileForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public ProfileForm $form;

    public function render()
    {
        return view('livewire.panel.profile.index', [
            'pageTitle' => 'Meu Perfil'
        ])->title($this->pageTitle);
    }

    public function mount()
    {
        $this->form->edit(Auth::user()->ulid);
    }

    public function rendered()
    {
        $this->dispatch('errors-save-profile', errors: $this->getErrorBag());

        $this->errorToastErrorBag();
        $this->resetErrorBag();
    }

    #[Computed]
    public function pageTitle()
    {
        return 'Meu Perfil';
    }

    public function submit()
    {
        $this->validate();

        try {
            $user = $this->form->save();

            session()->flash('success', 'Informações salvas com sucesso.');

            return $this->redirectRoute('panel.profile.index', [
                'ulid' => $user->ulid
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
