<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ProfileForm extends Form
{
    public ?string $ulid = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $at = '';
    public string $status = 'activated';
    public ?string $cpf_cnpj = null;
    public ?string $date_birth = null;

    public function isEditing(): bool
    {
        return filled($this->ulid);
    }

    public function edit(string $ulid): void
    {
        $user = $this->getUser($ulid);
        if (!$user) return;

        $this->ulid = $user->ulid;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->status = $user->status;
        $this->at = $user->at;
        $this->cpf_cnpj = $user->cpf_cnpj ?? '';
        $this->date_birth = $user->date_birth ? Carbon::createFromFormat('Y-m-d', $user->date_birth)->format('d/m/Y') : null;
    }

    public function save(): ?User
    {
        return $this->isEditing() ? $this->update() : $this->create();
    }

    protected function create(): ?User
    {
        return User::create($this->getUserData());
    }

    protected function update(): ?User
    {
        $user = $this->getUser($this->ulid);
        if (!$user) return null;

        $user->update($this->getUserData());
        return $user;
    }

    protected function getUserData(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => Auth::user()->role,
            'status' => $this->status,
            'at' => $this->at ?: Str::slug($this->name),
            'cpf_cnpj' => sanitizeSpecialCharacters($this->cpf_cnpj, true),
            'date_birth' => $this->date_birth ? Carbon::createFromFormat('d/m/Y', $this->date_birth)->format('Y-m-d')  : null,
        ];

        // Só altera senha se tiver preenchida
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        return $data;
    }

    private function getUser(string $ulid): ?User
    {
        return User::where('ulid', $ulid)->first();
    }

    protected function prepareForValidation($attributes): array
    {
        if (!empty($attributes['date_birth'])) {
            $attributes['date_birth'] = Carbon::createFromFormat('d/m/Y',  $attributes['date_birth'])->format('Y-m-d');
        }

        return $attributes;
    }

    protected function rules(): array
    {
        $userId = $this->ulid ? User::where('ulid', $this->ulid)->value('id') : null;

        $rules = [
            'name' => ['required'],
            'status' => ['required', 'in:activated,disabled'],
            'cpf_cnpj' => ['nullable', 'cpf_ou_cnpj'],
            'date_birth' => ['nullable', 'date'],
            'at' => [
                'required',
                'regex:/^[a-z0-9]+$/',
                Rule::unique('users', 'at')->ignore($userId),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [
                'nullable',
                'min:8',
                'regex:/[a-z]/', // Obriga ter pelo menos UMA letra minúscula (a até z)
                'regex:/[A-Z]/',  // Obriga ter pelo menos UMA letra maiúscula (A até Z
                'regex:/[0-9]/', // Obriga ter pelo menos UM número (0 até 9)
                'regex:/[@$!%*#?&]/', // Obriga ter pelo menos UM caractere especial "@ $ ! % * # ? &"
            ],
            'password_confirmation' => [
                'nullable',
                'same:password',
            ],
        ];

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'password_confirmation.same' => __('validation.confirmed'),
        ];
    }
}
