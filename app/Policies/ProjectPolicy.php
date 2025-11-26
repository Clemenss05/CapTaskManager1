<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * Determine if the user can view the project.
     * All authenticated users can view projects they're associated with.
     */
    public function view(User $user, Project $project): bool
    {
        // User can view if they are:
        // 1. The project owner
        // 2. An advisor (advisors can view all projects)
        // 3. Assigned to any task in the project
        
        if ($user->role === 'advisor' || $user->id === $project->owner_id) {
            return true;
        }
        
        // Check if user is assigned to any task in this project
        return $project->tasks()->where('assignee_id', $user->id)->exists();
    }

    /**
     * Determine if the user can update the project.
     * Only project owner (Team Leader) and Advisors can update projects.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->role === 'advisor' || $user->id === $project->owner_id;
    }

    /**
     * Determine if the user can delete the project.
     * Only Advisors can delete projects.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->role === 'advisor';
    }

    /**
     * Determine if the user can create a project.
     * Only Team Leaders and Advisors can create projects.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['leader', 'advisor']);
    }
}
