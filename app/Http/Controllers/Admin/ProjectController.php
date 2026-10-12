<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'task_filter' => ['nullable', 'in:all,with_tasks,without_tasks'],
        ]);

        $projects = Project::query()
            ->withCount('tasks')
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(($filters['task_filter'] ?? 'all') === 'with_tasks', fn (Builder $query) => $query->has('tasks'))
            ->when(($filters['task_filter'] ?? 'all') === 'without_tasks', fn (Builder $query) => $query->doesntHave('tasks'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.projects.form', ['project' => new Project]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string', 'max:5000']]);
        $request->user()->projects()->create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        return view('admin.projects.show', ['project' => $project->load('tasks.assignee')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        return view('admin.projects.form', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($request->validate(['name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string', 'max:5000']]));

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        DB::transaction(function () use ($project): void {
            $project->tasks()->delete();
            $project->delete();
        });

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }
}
