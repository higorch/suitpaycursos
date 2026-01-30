<?php

namespace App\Livewire\Panel\Course;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalFilter extends Component
{
    public $fields = [
        'name' => '',
        'creator' => '',
        'delivery_mode' => '',
        'max_enrollments' => '',
        'status' => '',
    ];

    public function render()
    {
        return view('livewire.panel.course.modal-filter', [
            'creators' => $this->creators
        ]);
    }

    #[On('run-modal-filter')]
    public function runModalFilter(array $fields = [])
    {
        $this->fields = array_merge($this->fields, $fields);
    }

    #[Computed]
    public function creators()
    {
        return User::has('courses')->get();
    }

    public function submit()
    {
        $this->dispatch('set-filter-fields', fields: $this->fields)->to(Index::class);
    }
}
