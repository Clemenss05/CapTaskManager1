<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming dashboard request.
     */
    public function __invoke()
    {
        $user = Auth::user();

        if ($user->isAdvisor()) {
            return $this->advisorDashboard($user);
        }

        return $this->teamDashboard($user);
    }

    private function teamDashboard(User $user)
    {
        $project = $this->getPrimaryProjectForUser($user);

        $metrics = [
            'totalTasks' => 0,
            'completedTasks' => 0,
            'percentComplete' => 0,
        ];

        $overdueTasks = collect();
        $actionRoute = route('projects.index');

        if ($project) {
            $totalTasks = Task::where('project_id', $project->id)->count();
            $completedTasks = Task::where('project_id', $project->id)
                ->where('status', 'done')
                ->count();

            $metrics = [
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'percentComplete' => $totalTasks > 0
                    ? round(($completedTasks / $totalTasks) * 100)
                    : 0,
            ];

            $overdueTasks = Task::where('project_id', $project->id)
                ->where('status', '!=', 'done')
                ->orderBy('updated_at')
                ->take(3)
                ->get();

            $actionRoute = route('projects.show', $project);
        }

        return view('dashboard', [
            'mode' => 'team',
            'project' => $project,
            'metrics' => $metrics,
            'overdueTasks' => $overdueTasks,
            'actionLabel' => 'Go to My Project',
            'actionRoute' => $actionRoute,
        ]);
    }

    private function advisorDashboard(User $user)
    {
        $projects = Project::with('owner')
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn ($query) => $query->where('status', 'done'),
                'tasks as open_tasks' => fn ($query) => $query->where('status', '!=', 'done'),
            ])
            ->get()
            ->map(function ($project) {
                $total = (int) $project->total_tasks;
                $completed = (int) $project->completed_tasks;
                $project->progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                return $project;
            })
            ->sortBy('progress')
            ->values();

        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'done')->count();

        $metrics = [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'percentComplete' => $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0,
        ];

        return view('dashboard', [
            'mode' => 'advisor',
            'metrics' => $metrics,
            'projectsByNeed' => $projects,
            'actionLabel' => 'Review All Projects',
            'actionRoute' => route('projects.index'),
        ]);
    }

    private function getPrimaryProjectForUser(User $user): ?Project
    {
        if ($user->isLeader()) {
            return Project::with('owner')
                ->where('owner_id', $user->id)
                ->first();
        }

        if ($user->isMember()) {
            return Project::with('owner')
                ->whereHas('tasks', fn ($query) => $query->where('assignee_id', $user->id))
                ->first();
        }

        return Project::with('owner')->first();
    }
}
