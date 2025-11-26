<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TaskModal extends Component
{
    public $taskId;

    public function render()
    {
        return view('livewire.task-modal');
    }
}
