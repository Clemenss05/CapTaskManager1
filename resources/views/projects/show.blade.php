
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $project->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Owner: {{ $project->owner->name }}
                </p>
            </div>
            <a href="{{ route('projects.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back to Projects
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Description</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $project->description }}</p>
                </div>

                @if(Auth::user()->can('create', App\Models\Task::class))
                    <div class="mb-4 flex justify-end">
                        <button 
                            onclick="Livewire.dispatch('openTaskModal')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            + NEW TASK
                        </button>
                    </div>
                @endif

                <livewire:kanban-board :projectId="$project->id" :key="'kanban-'.$project->id" />
                <livewire:task-modal :projectId="$project->id" :key="'task-modal-'.$project->id" />
            </div>
        </div>
    </div>
</x-app-layout>
