<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::with(['project', 'assignee'])
            ->when(! $request->user()->isAdmin(), fn ($query) => $query->where('assigned_to', $request->user()->id))
            ->latest()->paginate(20);

        return response()->json($tasks);
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        abort_unless($request->user()->isAdmin() || $task->assigned_to === $request->user()->id, 404);

        return response()->json($task->load(['project', 'assignee']));
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'due_date' => ['nullable', 'date'],
            'assigned_to' => ['required', 'exists:users,id,role,employee'],
        ]);
        $task = $request->user()->createdTasks()->create($data);

        return response()->json($task->load(['project', 'assignee']), 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        if ($request->user()->isAdmin()) {
            $task->update($request->validate([
                'project_id' => ['sometimes', 'exists:projects,id'],
                'title' => ['sometimes', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'priority' => ['sometimes', 'in:low,medium,high'],
                'status' => ['sometimes', 'in:pending,in_progress,completed'],
                'due_date' => ['nullable', 'date'],
                'assigned_to' => ['sometimes', 'exists:users,id,role,employee'],
            ]));
        } else {
            abort_unless($task->assigned_to === $request->user()->id, 404);
            $task->update($request->validate(['status' => ['required', 'in:pending,in_progress,completed']]));
        }

        return response()->json($task->fresh()->load(['project', 'assignee']));
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        abort_unless($request->user()->isAdmin(), 403);
        $task->delete();

        return response()->json(['message' => 'Task deleted.']);
    }
}
