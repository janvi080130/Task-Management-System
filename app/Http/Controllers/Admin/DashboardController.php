<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'projects' => Project::count(),
            'tasks' => Task::count(),
            'open' => Task::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed' => Task::where('status', 'completed')->count(),
        ];
        $recentTasks = Task::with(['project', 'assignee'])->latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'recentTasks'));
    }
}
