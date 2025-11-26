<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskModal extends Component
{
    public $isOpen = false;
    public $taskId = null;
    public $projectId;
    public $title = '';
    public $description = '';
    public $due_date = '';
    public $assignee_id = '';
    public $status = 'todo';
    
    public $users = [];

    protected $listeners = ['openTaskModal', 'editTask'];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadUsers();
    }

    public function loadUsers()
    {
        // Load all users for assignment dropdown
        $this->users = User::orderBy('name')->get();
    }

    public function openTaskModal()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function editTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        
        // Verify task belongs to the current project
        if ($task->project_id != $this->projectId) {
            return;
        }
        
        // Check if user can update this task
        if (!Auth::user()->can('update', $task)) {
            session()->flash('error', 'You do not have permission to edit this task.');
            return;
        }
        
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description ?? '';
        $this->due_date = $task->due_date ?? '';
        $this->assignee_id = $task->assignee_id ?? '';
        $this->status = $task->status;
        
        $this->isOpen = true;
    }

    public function saveTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        if ($this->taskId) {
            // Update existing task
            $task = Task::findOrFail($this->taskId);
            
            // Verify task belongs to the current project
            if ($task->project_id != $this->projectId) {
                session()->flash('error', 'Unauthorized action.');
                return;
            }
            
            // Check authorization
            if (!Auth::user()->can('update', $task)) {
                session()->flash('error', 'You do not have permission to update this task.');
                return;
            }
            
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
                'due_date' => $this->due_date ?: null,
                'assignee_id' => $this->assignee_id ?: null,
            ]);
            
            session()->flash('message', 'Task updated successfully.');
        } else {
            // Check if user can create tasks
            if (!Auth::user()->can('create', Task::class)) {
                session()->flash('error', 'You do not have permission to create tasks.');
                return;
            }
            
            // Create new task with default status 'todo'
            $maxOrder = Task::where('project_id', $this->projectId)
                ->where('status', 'todo')
                ->max('order') ?? -1;
            
            Task::create([
                'project_id' => $this->projectId,
                'title' => $this->title,
                'description' => $this->description,
                'due_date' => $this->due_date ?: null,
                'assignee_id' => $this->assignee_id ?: null,
                'status' => 'todo',
                'order' => $maxOrder + 1,
            ]);
            
            session()->flash('message', 'Task created successfully.');
        }

        $this->closeModal();
        
        // Emit event to refresh the Kanban board
        $this->dispatch('taskUpdated');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function deleteTask()
    {
        if (!$this->taskId) {
            return;
        }
        
        $task = Task::findOrFail($this->taskId);
        
        // Check if user can delete this task
        if (!Auth::user()->can('delete', $task)) {
            session()->flash('error', 'You do not have permission to delete this task.');
            return;
        }
        
        $task->delete();
        
        session()->flash('message', 'Task deleted successfully.');
        $this->closeModal();
        $this->dispatch('taskUpdated');
    }

    private function resetForm()
    {
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->due_date = '';
        $this->assignee_id = '';
        $this->status = 'todo';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.task-modal');
    }
}
