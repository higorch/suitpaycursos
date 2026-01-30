<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    #[Layout('layouts::auth')]
    #[Title('Entrar')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function rendered()
    {
        $this->dispatch('errors-login-user', errors: $this->getErrorBag());

        $this->errorToastErrorBag();
        $this->resetErrorBag();
    }

    public function submit()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
            'status' => 'activated',
        ];

        if (!Auth::attempt($credentials, $this->remember)) {
            $this->dispatch('notify', msg: "E-mail ou senha incorretos.", type: "error");
        } else {
            request()->session()->regenerate();

            $role = Auth::user()->role;

            if (in_array($role, ['admin', 'creator'])) {
                $this->redirectRoute('panel.profile.index', navigate: true);
                return;
            }

            if ($role === 'student') {
                $this->redirectRoute('student.profile.index', navigate: true);
                return;
            }
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
