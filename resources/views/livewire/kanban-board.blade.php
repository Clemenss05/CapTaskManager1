<div class="w-full">
    <!-- Debug Info -->
    <div class="mb-4 p-3 bg-yellow-100 border border-yellow-400 rounded text-sm">
        <strong>Debug:</strong> 
        Project ID: {{ $projectId ?? 'NULL' }} | 
        Todo: {{ count($todoTasks ?? []) }} | 
        In Progress: {{ count($inProgressTasks ?? []) }} | 
        Done: {{ count($doneTasks ?? []) }}
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- To Do Column -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">To Do</h3>
                <span class="inline-flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2.5 py-1">
                    {{ count($todoTasks ?? []) }}
                </span>
            </div>
            <div
                class="space-y-3 min-h-[16rem] rounded-lg"
                x-data
                x-init="
                    let sortable = new Sortable($el, {
                        group: 'kanban',
                        animation: 150,
                        ghostClass: 'opacity-50',
                        handle: '.drag-handle',
                        onEnd: function(evt) {
                            let items = Array.from(evt.to.children)
                                .map(el => el.dataset.taskId)
                                .filter(id => id && id !== 'undefined');
                            let status = evt.to.dataset.status;
                            console.log('Drag ended:', { items, status });
                            @this.call('updateTaskOrder', items, status);
                        }
                    });
                "
                data-status="todo"
            >
                @forelse(($todoTasks ?? []) as $task)
                    <div
                        class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition p-4"
                        data-task-id="{{ $task['id'] }}"
                        wire:key="task-{{ $task['id'] }}"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div onclick="Livewire.dispatch('editTask', { taskId: {{ $task['id'] }} })" class="flex-1 cursor-pointer">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $task['title'] }}</h4>
                                @if(!empty($task['description']))
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $task['description'] }}</p>
                                @endif
                                @if(!empty($task['assignee']))
                                    <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $task['assignee']['name'] }}
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="drag-handle shrink-0 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-grab">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7 4a1 1 0 100-2 1 1 0 000 2zm6-1a1 1 0 110 2 1 1 0 010-2zM7 11a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 110 2 1 1 0 010-2zM7 18a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 110 2 1 1 0 010-2z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 dark:text-gray-400 text-center py-10">No tasks</div>
                @endforelse
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200/60 dark:border-blue-800/50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">In Progress</h3>
                <span class="inline-flex items-center justify-center rounded-full bg-blue-200 dark:bg-blue-800 text-blue-700 dark:text-blue-300 text-xs px-2.5 py-1">{{ count($inProgressTasks ?? []) }}</span>
            </div>
            <div
                class="space-y-3 min-h-[16rem] rounded-lg"
                x-data
                x-init="
                    let sortable = new Sortable($el, {
                        group: 'kanban',
                        animation: 150,
                        ghostClass: 'opacity-50',
                        handle: '.drag-handle',
                        onEnd: function(evt) {
                            let items = Array.from(evt.to.children)
                                .map(el => el.dataset.taskId)
                                .filter(id => id && id !== 'undefined');
                            let status = evt.to.dataset.status;
                            console.log('Drag ended:', { items, status });
                            @this.call('updateTaskOrder', items, status);
                        }
                    });
                "
                data-status="in-progress"
            >
                @forelse(($inProgressTasks ?? []) as $task)
                    <div
                        class="bg-white dark:bg-gray-900 rounded-lg border border-blue-200 dark:border-blue-700 shadow-sm hover:shadow-md transition p-4"
                        data-task-id="{{ $task['id'] }}"
                        wire:key="task-{{ $task['id'] }}"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div onclick="Livewire.dispatch('editTask', { taskId: {{ $task['id'] }} })" class="flex-1 cursor-pointer">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $task['title'] }}</h4>
                                @if(!empty($task['description']))
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $task['description'] }}</p>
                                @endif
                                @if(!empty($task['assignee']))
                                    <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $task['assignee']['name'] }}
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="drag-handle shrink-0 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-grab">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7 4a1 1 0 100-2 1 1 0 000 2zm6-1a1 1 0 110 2 1 1 0 010-2zM7 11a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 110 2 1 1 0 010-2zM7 18a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 110 2 1 1 0 010-2z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 dark:text-gray-400 text-center py-10">No tasks</div>
                @endforelse
            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-200/60 dark:border-green-800/40">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Done</h3>
                <span class="inline-flex items-center justify-center rounded-full bg-green-200 dark:bg-green-800 text-green-700 dark:text-green-300 text-xs px-2.5 py-1">{{ count($doneTasks ?? []) }}</span>
            </div>
            <div
                class="space-y-3 min-h-[16rem] rounded-lg"
                x-data
                x-init="
                    let sortable = new Sortable($el, {
                        group: 'kanban',
                        animation: 150,
                        ghostClass: 'opacity-50',
                        handle: '.drag-handle',
                        onEnd: function(evt) {
                            let items = Array.from(evt.to.children)
                                .map(el => el.dataset.taskId)
                                .filter(id => id && id !== 'undefined');
                            let status = evt.to.dataset.status;
                            console.log('Drag ended:', { items, status });
                            @this.call('updateTaskOrder', items, status);
                        }
                    });
                "
                data-status="done"
            >
                @forelse(($doneTasks ?? []) as $task)
                    <div
                        class="bg-white dark:bg-gray-900 rounded-lg border border-green-200 dark:border-green-700 shadow-sm hover:shadow-md transition p-4"
                        data-task-id="{{ $task['id'] }}"
                        wire:key="task-{{ $task['id'] }}"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div onclick="Livewire.dispatch('editTask', { taskId: {{ $task['id'] }} })" class="flex-1 cursor-pointer">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $task['title'] }}</h4>
                                @if(!empty($task['description']))
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $task['description'] }}</p>
                                @endif
                                @if(!empty($task['assignee']))
                                    <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $task['assignee']['name'] }}
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="drag-handle shrink-0 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 cursor-grab">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7 4a1 1 0 100-2 1 1 0 000 2zm6-1a1 1 0 110 2 1 1 0 010-2zM7 11a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 110 2 1 1 0 010-2zM7 18a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 110 2 1 1 0 010-2z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 dark:text-gray-400 text-center py-10">No tasks</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

