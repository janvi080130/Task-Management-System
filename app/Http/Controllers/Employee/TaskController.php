<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        abort_if($request->user()->isAdmin(), 403);
        $tasks = $request->user()->assignedTasks()->with('project')->latest()->paginate(12);

        return view('employee.tasks.index', compact('tasks'));
    }

    public function show(Request $request, Task $task): View
    {
        abort_if($request->user()->isAdmin(), 403);
        abort_unless($task->assigned_to === $request->user()->id, 404);

        return view('employee.tasks.show', ['task' => $task->load('project')]);
    }

    public function updateStatus(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        abort_if($request->user()->isAdmin(), 403);
        abort_unless($task->assigned_to === $request->user()->id, 404);
        $validated = $request->validate(['status' => ['required', 'in:pending,in_progress,completed']]);
        $task->update(['status' => $validated['status']]);

        if ($request->expectsJson()) {
            return response()->json(['status' => $task->status, 'message' => 'Task status updated.']);
        }

        return back()->with('success', 'Task status updated.');
    }
}
