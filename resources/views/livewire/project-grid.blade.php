<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Projects</label>
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search by project name" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        @if($isAdvisor && !empty($leaders))
            <div class="md:w-64">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Team Leader</label>
                <select wire:model="leaderFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All leaders</option>
                    @foreach($leaders as $leader)
                        <option value="{{ $leader['id'] }}">{{ $leader['name'] }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    @if(empty($projects))
        <p class="text-gray-600 dark:text-gray-400">No projects found.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <a href="{{ route('projects.show', $project['id']) }}" class="group relative block rounded-2xl border border-gray-200/80 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg transition">
                    <div class="p-6 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500">Project</p>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $project['name'] }}</h3>
                                @if($isAdvisor)
                                    <p class="text-sm text-gray-500">Lead: {{ $project['owner_name'] }}</p>
                                @endif
                            </div>
                            <span class="text-xs font-semibold inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $project['status']['bg'] }}">
                                <span class="w-2 h-2 rounded-full {{ $project['status']['dot'] }}"></span>
                                {{ $project['status']['label'] }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                            {{ $project['description'] ?? 'No description provided.' }}
                        </p>

                        <div class="flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-200">
                            <span>Tasks: {{ $project['total_tasks'] }}</span>
                            <span>Done: {{ $project['completed_tasks'] }}</span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                <span>Progress</span>
                                <span>{{ $project['progress'] }}%</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500" style="width: {{ $project['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/40 text-sm text-indigo-600 dark:text-indigo-300 font-semibold flex items-center justify-between">
                        <span>Open Kanban</span>
                        <span class="transition-transform group-hover:translate-x-1">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
