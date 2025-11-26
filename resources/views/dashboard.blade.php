<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm uppercase text-gray-500 dark:text-gray-400">Total Tasks</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['totalTasks'] ?? 0) }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm uppercase text-gray-500 dark:text-gray-400">Completed</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($metrics['completedTasks'] ?? 0) }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm uppercase text-gray-500 dark:text-gray-400">% Complete</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $metrics['percentComplete'] ?? 0 }}%
                    </p>
                </div>
            </div>

            <div>
                <a href="{{ $actionRoute }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $actionLabel }}
                </a>
            </div>

            @if(($mode ?? '') === 'team')
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div>
                            <p class="text-sm uppercase text-gray-500">Current Project</p>
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">
                                {{ $project->name ?? 'No project assigned' }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mt-2">
                                Owner: {{ $project?->owner?->name ?? 'TBD' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Overall Progress</p>
                            <p class="text-5xl font-bold text-gray-900 dark:text-white">
                                {{ $metrics['percentComplete'] ?? 0 }}%
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $metrics['completedTasks'] ?? 0 }} / {{ $metrics['totalTasks'] ?? 0 }} tasks done
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $metrics['percentComplete'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Most Overdue Tasks</h4>
                            <div class="bg-gray-50 dark:bg-gray-900/40 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                @forelse($overdueTasks as $task)
                                    <div class="py-3 flex items-center justify-between border-b last:border-0 border-gray-200 dark:border-gray-700">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $task->title }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Updated {{ optional($task->updated_at)->diffForHumans() }}</p>
                                        </div>
                                        <span class="text-xs font-semibold uppercase px-3 py-1 rounded-full bg-amber-100 text-amber-800">
                                            {{ str_replace('-', ' ', $task->status) }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">All tasks are on track. 🎉</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl p-6 border border-indigo-100 dark:border-indigo-800">
                            <h4 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100">Next Steps</h4>
                            <p class="text-sm text-indigo-900/80 dark:text-indigo-100/80 mt-2">
                                Keep momentum by tackling the top overdue tasks or updating progress in the Kanban board.
                            </p>
                            <a href="{{ $actionRoute }}" class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-700 dark:text-indigo-200">
                                Jump to project →
                            </a>
                        </div>
                    </div>
                </div>
            @elseif(($mode ?? '') === 'advisor')
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Teams needing attention</h3>
                            <p class="text-sm text-gray-500 mt-1">Sorted by lowest progress.</p>
                        </div>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse($projectsByNeed as $project)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-2xl p-5 flex flex-col gap-4">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="text-sm uppercase text-gray-500">Project</p>
                                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $project->name }}</h4>
                                        <p class="text-sm text-gray-500">Owner: {{ $project->owner?->name ?? 'Unassigned' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs uppercase text-gray-500">Progress</p>
                                        <p class="text-4xl font-bold text-rose-600">{{ $project->progress }}%</p>
                                        <p class="text-xs text-gray-500">{{ $project->completed_tasks }} / {{ $project->total_tasks }} done</p>
                                    </div>
                                </div>
                                <div>
                                    <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-rose-500" style="width: {{ $project->progress }}%"></div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $project->open_tasks }} tasks need attention
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-500">Updated {{ optional($project->updated_at)->diffForHumans() ?? 'recently' }}</p>
                                    <a href="{{ route('projects.show', $project) }}" class="text-sm font-semibold text-rose-600 hover:text-rose-700">View project →</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No projects found.</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
