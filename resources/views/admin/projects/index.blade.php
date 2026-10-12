<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Projects</h1>
            <a href="{{ route('admin.projects.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-white">New project</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5 px-4 py-8">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow-sm">
            <label class="min-w-60 flex-1 text-sm font-medium">Search projects
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Name or description" class="mt-1 block w-full rounded-lg border-slate-300">
            </label>
            <label class="text-sm font-medium">Task availability
                <select name="task_filter" class="mt-1 block w-full rounded-lg border-slate-300">
                    <option value="all" @selected(request('task_filter', 'all') === 'all')>All projects</option>
                    <option value="with_tasks" @selected(request('task_filter') === 'with_tasks')>With tasks</option>
                    <option value="without_tasks" @selected(request('task_filter') === 'without_tasks')>Without tasks</option>
                </select>
            </label>
            <button class="rounded-lg bg-slate-800 px-4 py-2 text-white">Filter</button>
            <a href="{{ route('admin.projects.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-slate-700">Clear</a>
        </form>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left">
                <thead class="text-sm text-slate-500"><tr><th class="p-4">Project</th><th class="p-4">Tasks</th><th class="p-4">Actions</th></tr></thead>
                <tbody class="divide-y">
                    @forelse($projects as $project)
                        <tr>
                            <td class="p-4"><a class="font-medium text-indigo-700" href="{{ route('admin.projects.show', $project) }}">{{ $project->name }}</a><p class="text-sm text-slate-500">{{ Str::limit($project->description, 90) }}</p></td>
                            <td class="p-4">{{ $project->tasks_count }}</td>
                            <td class="p-4">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="text-indigo-700">Edit</a>
                                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="inline" onsubmit="return confirm('Deleting this project will also permanently delete all of its tasks. Continue?')">
                                    @csrf @method('DELETE')
                                    <button class="ml-3 text-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-8 text-center text-slate-500">No projects match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $projects->links() }}</div>
    </div>
</x-app-layout>
