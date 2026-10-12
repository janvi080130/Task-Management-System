<x-app-layout>
    <x-slot name="header"><div class="flex items-center justify-between"><h1 class="text-2xl font-semibold text-slate-800">Tasks</h1><a class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white" href="{{ route('admin.tasks.create') }}">Create task</a></div></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))<p class="rounded-lg bg-emerald-50 p-3 text-emerald-800">{{ session('success') }}</p>@endif
        <form class="flex flex-wrap gap-3 rounded-xl bg-white p-4 shadow-sm"><input name="search" value="{{ request('search') }}" placeholder="Search task title" class="rounded-lg border-slate-300"><select name="status" class="rounded-lg border-slate-300"><option value="">Any status</option>@foreach(['pending','in_progress','completed'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select><select name="priority" class="rounded-lg border-slate-300"><option value="">Any priority</option>@foreach(['low','medium','high'] as $priority)<option @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select><button class="rounded-lg bg-slate-800 px-4 py-2 text-white">Filter</button></form>
        <div class="rounded-xl bg-white p-6 shadow-sm">@include('shared.task-table', ['tasks' => $tasks, 'admin' => true])<div class="mt-5">{{ $tasks->links() }}</div></div>
    </div>
</x-app-layout>
