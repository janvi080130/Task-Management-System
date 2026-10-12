<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
        <thead class="text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="py-3 pr-4">Task</th>
                <th class="py-3 pr-4">Project</th>
                @if ($admin)
                    <th class="py-3 pr-4">Assigned to</th>
                @endif
                <th class="py-3 pr-4">Priority</th>
                <th class="py-3 pr-4">Status</th>
                <th class="py-3">Due</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tasks as $task)
                <tr>
                    <td class="py-3 pr-4 font-medium">
                        <a class="text-indigo-700 hover:underline" href="{{ $admin ? route('admin.tasks.show', $task) : route('employee.tasks.show', $task) }}">{{ $task->title }}</a>
                    </td>
                    <td class="py-3 pr-4">{{ $task->project->name }}</td>
                    @if ($admin)
                        <td class="py-3 pr-4">{{ $task->assignee->name }}</td>
                    @endif
                    <td class="py-3 pr-4">
                        <span @class([
                            'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize',
                            'bg-emerald-100 text-emerald-800' => $task->priority === 'low',
                            'bg-amber-100 text-amber-800' => $task->priority === 'medium',
                            'bg-rose-100 text-rose-800' => $task->priority === 'high',
                        ])>{{ $task->priority }}</span>
                    </td>
                    <td class="py-3 pr-4">
                        <span @class([
                            'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                            'bg-slate-100 text-slate-700' => $task->status === 'pending',
                            'bg-sky-100 text-sky-800' => $task->status === 'in_progress',
                            'bg-emerald-100 text-emerald-800' => $task->status === 'completed',
                        ])>{{ str_replace('_', ' ', ucfirst($task->status)) }}</span>
                    </td>
                    <td class="py-3">{{ $task->due_date?->format('M j, Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">No tasks found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
