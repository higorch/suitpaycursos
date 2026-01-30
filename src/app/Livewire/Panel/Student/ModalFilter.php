<?php

namespace App\Livewire\Panel\Student;

use Livewire\Attributes\On;
use Livewire\Component;

class ModalFilter extends Component
{
    public $fields = [
        'name' => '',
        'email' => '',
        'cpf_cnpj' => '',
        'status' => ''
    ];

    public function render()
    {
        return view('livewire.panel.student.modal-filter');
    }

    #[On('run-modal-filter')]
    public function runModalFilter(array $fields = [])
    {
        $this->fields = array_merge($this->fields, $fields);
    }

    public function submit()
    {
        $this->dispatch('set-filter-fields', fields: $this->fields)->to(Index::class);
    }
}
