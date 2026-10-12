<x-app-layout><x-slot name="header"><h1 class="text-2xl font-semibold">{{ $task->title }}</h1></x-slot><div class="mx-auto max-w-3xl px-4 py-8"><article class="space-y-5 rounded-xl bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">{{ $task->project->name }}</p><p>{{ $task->description ?: 'No description provided.' }}</p><div class="grid gap-4 sm:grid-cols-3"><p><span class="block text-sm text-slate-500">Priority</span><span class="capitalize">{{ $task->priority }}</span></p><p><span class="block text-sm text-slate-500">Due date</span>{{ $task->due_date?->format('M j, Y') ?? '—' }}</p><label><span class="block text-sm text-slate-500">Status <span class="text-red-600" aria-hidden="true">*</span></span><select id="task-status" required data-url="{{ route('employee.tasks.updateStatus',$task) }}" class="mt-1 rounded-lg border-slate-300">@foreach(['pending','in_progress','completed'] as $status)<option value="{{ $status }}" @selected($task->status === $status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select><p id="status-message" class="mt-1 text-sm text-slate-500" aria-live="polite"></p></label></div></article></div>
<script>
document.getElementById('task-status').addEventListener('change', async (event) => {
    const select = event.currentTarget;
    const message = document.getElementById('status-message');
    message.textContent = 'Saving…';
    try {
        const response = await fetch(select.dataset.url, {
            method: 'PATCH',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({status: select.value})
        });
        const result = await response.json();
        if (!response.ok) {
            message.className = 'text-sm text-red-600';
            throw new Error(result.message || 'Could not update status.');
        }
        message.className = 'text-sm text-emerald-700';
        message.textContent = result.message;
    } catch (error) {
        message.textContent = error.message;
    }
});
</script></x-app-layout>
