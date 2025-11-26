<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view tasks
        return true;
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        // Users can view tasks if they are:
        // 1. The project owner (Team Leader)
        // 2. An advisor
        // 3. Assigned to the task
        
        if ($user->role === 'advisor') {
            return true;
        }
        
        if ($task->project->owner_id === $user->id) {
            return true;
        }
        
        return $task->assignee_id === $user->id;
    }

    /**
     * Determine whether the user can create tasks.
     * Only Team Leaders and Advisors can create tasks.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['leader', 'advisor']);
    }

    /**
     * Determine whether the user can update the task.
     * - Team Leader (project owner) can update all task fields
     * - Assigned user can update all task fields
     * - Advisor can update task status only
     */
    public function update(User $user, Task $task): bool
    {
        // Team Leader (project owner) can update everything
        if ($task->project->owner_id === $user->id) {
            return true;
        }
        
        // Assigned user can update everything
        if ($task->assignee_id === $user->id) {
            return true;
        }
        
        // Advisor can update (status updates handled separately in updateStatus)
        if ($user->role === 'advisor') {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update only the task status.
     * Advisors can update task status even if they can't update other fields.
     */
    public function updateStatus(User $user, Task $task): bool
    {
        // Advisors can always update status
        if ($user->role === 'advisor') {
            return true;
        }
        
        // Otherwise, defer to regular update permission
        return $this->update($user, $task);
    }

    /**
     * Determine whether the user can delete the task.
     * Only the Team Leader (project owner) can delete tasks.
     */
    public function delete(User $user, Task $task): bool
    {
        // Only the Team Leader (project owner) can delete tasks
        return $task->project->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the task.
     */
    public function restore(User $user, Task $task): bool
    {
        // Only Team Leader can restore
        return $task->project->owner_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the task.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        // Only Advisors can force delete
        return $user->role === 'advisor';
    }
}
