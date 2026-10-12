<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tasks = Task::with(['project', 'assignee'])->when(request('search'), function ($query, $search) {
            $query->where('title', 'like', "%{$search}%");
        })->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->when(request('priority'), fn ($query, $priority) => $query->where('priority', $priority))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tasks.create', ['projects' => Project::orderBy('name')->get(), 'employees' => User::where('role', 'employee')->orderBy('name')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create([...$request->validated(), 'created_by' => $request->user()->id]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        return view('admin.tasks.show', ['task' => $task->load(['project', 'assignee', 'creator'])]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', ['task' => $task, 'projects' => Project::orderBy('name')->get(), 'employees' => User::where('role', 'employee')->orderBy('name')->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted.');
    }
}
