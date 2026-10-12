<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-semibold text-slate-800">Admin dashboard</h1></x-slot>
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([['Projects', $stats['projects']], ['All tasks', $stats['tasks']], ['Open tasks', $stats['open']], ['Completed', $stats['completed']]] as [$label, $value])
                <div class="rounded-xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-semibold text-slate-900">{{ $value }}</p></div>
            @endforeach
        </div>
        <section class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between"><h2 class="text-lg font-semibold">Recent tasks</h2><a class="text-sm font-medium text-indigo-700" href="{{ route('admin.tasks.index') }}">View all</a></div>
            @include('shared.task-table', ['tasks' => $recentTasks, 'admin' => true])
        </section>
    </div>
</x-app-layout>
