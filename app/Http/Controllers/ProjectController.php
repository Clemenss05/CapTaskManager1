<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('owner')
            ->withCount('tasks')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $project->load(['tasks.assignee', 'owner']);

        return view('projects.show', compact('project'));
    }
}
