<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class KanbanBoard extends Component
{
    public $projectId;
    public $todoTasks = [];
    public $inProgressTasks = [];
    public $doneTasks = [];

    protected $listeners = ['taskUpdated' => 'loadTasks'];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        
        Log::info('KanbanBoard mount called', [
            'projectId' => $this->projectId,
        ]);
        
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $tasks = Task::where('project_id', $this->projectId)
            ->with('assignee')
            ->orderBy('order')
            ->get();

        $this->todoTasks = $tasks->where('status', 'todo')->values()->toArray();
        $this->inProgressTasks = $tasks->where('status', 'in-progress')->values()->toArray();
        $this->doneTasks = $tasks->where('status', 'done')->values()->toArray();

        // Debug log to verify data loaded correctly
        Log::info('KanbanBoard loadTasks', [
            'projectId' => $this->projectId,
            'total' => $tasks->count(),
            'todo' => count($this->todoTasks),
            'in_progress' => count($this->inProgressTasks),
            'done' => count($this->doneTasks),
        ]);
    }

    public function updateTaskStatus($taskId, $newStatus)
    {
        $task = Task::find($taskId);
        
        if (!$task || $task->project_id != $this->projectId) {
            return;
        }
        
        // Check if user has permission to update task status
        if (!Auth::user()->can('updateStatus', $task)) {
            Log::warning('Unauthorized task status update attempt', [
                'user_id' => Auth::id(),
                'task_id' => $taskId,
            ]);
            return;
        }
        
        $task->status = $newStatus;
        $task->save();
        $this->loadTasks();
    }

    public function updateTaskOrder($orderedIds, $status)
    {
        // $items is an array of task IDs in their new order
        // Update the order for all tasks in this column
        Log::info('KanbanBoard updateTaskOrder start', [
            'projectId' => $this->projectId,
            'status' => $status,
            'orderedIds' => $orderedIds,
        ]);
        foreach ($orderedIds as $index => $taskId) {
            $task = Task::find($taskId);
            
            // Verify task exists and belongs to project
            if (!$task || $task->project_id != $this->projectId) {
                continue;
            }
            
            // Check permission
            if (!Auth::user()->can('updateStatus', $task)) {
                continue;
            }
            
            $task->update([
                'order' => $index,
                'status' => $status
            ]);
        }
        
        Log::info('KanbanBoard updateTaskOrder done', [
            'status' => $status,
        ]);
        $this->loadTasks();
    }

    public function moveTask($taskId, $fromStatus, $toStatus, $newOrder)
    {
        $task = Task::find($taskId);
        
        if (!$task || $task->project_id != $this->projectId) {
            return;
        }
        
        // Check if user has permission to update task status
        if (!Auth::user()->can('updateStatus', $task)) {
            Log::warning('Unauthorized task move attempt', [
                'user_id' => Auth::id(),
                'task_id' => $taskId,
            ]);
            return;
        }
        
        Log::info('KanbanBoard moveTask start', [
            'task_id' => $taskId,
            'from' => $fromStatus,
            'to' => $toStatus,
            'newOrder' => $newOrder,
        ]);

        // Update the task's status and order
        $task->status = $toStatus;
        $task->order = $newOrder;
        $task->save();
        
        // Reorder remaining tasks in the source column
        Task::where('project_id', $this->projectId)
            ->where('status', $fromStatus)
            ->where('id', '!=', $taskId)
            ->orderBy('order')
            ->get()
            ->each(function ($t, $index) {
                $t->order = $index;
                $t->save();
            });
        
        // Reorder tasks in the destination column
        Task::where('project_id', $this->projectId)
            ->where('status', $toStatus)
            ->orderBy('order')
            ->get()
            ->each(function ($t, $index) {
                $t->order = $index;
                $t->save();
            });
        
        Log::info('KanbanBoard moveTask done', [
            'task_id' => $taskId,
            'to' => $toStatus,
        ]);
        $this->loadTasks();
    }

    public function render()
    {
        Log::info('KanbanBoard render called', [
            'todoCount' => count($this->todoTasks),
            'inProgressCount' => count($this->inProgressTasks),
            'doneCount' => count($this->doneTasks),
        ]);
        
        return view('livewire.kanban-board', [
            'todoTasks' => $this->todoTasks,
            'inProgressTasks' => $this->inProgressTasks,
            'doneTasks' => $this->doneTasks,
        ]);
    }
}
