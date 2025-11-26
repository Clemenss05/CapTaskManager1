<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class ProjectGrid extends Component
{
    public $projects = [];
    public $isAdvisor = false;
    public $search = '';
    public $leaderFilter = '';
    public $leaders = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->isAdvisor = $user?->isAdvisor() ?? false;

        if ($this->isAdvisor) {
            $this->leaders = User::where('role', 'leader')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();
        }

        $this->loadProjects();
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'leaderFilter'])) {
            $this->loadProjects();
        }
    }

    private function loadProjects(): void
    {
        $query = Project::with('owner')
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn ($builder) => $builder->where('status', 'done'),
                'tasks as in_progress_tasks' => fn ($builder) => $builder->where('status', 'in-progress'),
            ]);

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->isAdvisor && ! empty($this->leaderFilter)) {
            $query->where('owner_id', $this->leaderFilter);
        }

        $this->projects = $query
            ->orderBy('name')
            ->get()
            ->map(fn ($project) => $this->formatProject($project))
            ->toArray();
    }

    public function render()
    {
        return view('livewire.project-grid', [
            'projects' => $this->projects,
            'leaders' => $this->leaders,
            'isAdvisor' => $this->isAdvisor,
        ]);
    }

    private function formatProject(Project $project): array
    {
        $total = (int) ($project->total_tasks ?? 0);
        $completed = (int) ($project->completed_tasks ?? 0);
        $inProgress = (int) ($project->in_progress_tasks ?? 0);
        $open = max($total - $completed, 0);
        $progress = $total > 0 ? round(($completed / $total) * 100) : 0;

        $status = $this->statusBadge($progress, $open, $inProgress);

        return [
            'id' => $project->id,
            'name' => $project->name,
            'description' => Str::limit($project->description, 160),
            'owner_name' => $project->owner->name ?? 'Unassigned',
            'total_tasks' => $total,
            'completed_tasks' => $completed,
            'progress' => $progress,
            'status' => $status,
        ];
    }

    private function statusBadge(int $progress, int $openTasks, int $inProgress): array
    {
        if ($progress >= 75 || ($openTasks <= 3 && $inProgress <= 1)) {
            return [
                'label' => 'On Track',
                'bg' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                'dot' => 'bg-emerald-500',
            ];
        }

        if ($progress >= 45) {
            return [
                'label' => 'Minor Delays',
                'bg' => 'bg-amber-100 text-amber-700 border border-amber-200',
                'dot' => 'bg-amber-500',
            ];
        }

        return [
            'label' => 'At Risk',
            'bg' => 'bg-rose-100 text-rose-700 border border-rose-200',
            'dot' => 'bg-rose-500',
        ];
    }
}
