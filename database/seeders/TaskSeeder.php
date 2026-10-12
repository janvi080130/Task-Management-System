<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $tasks = [
            ['title' => 'Prepare homepage wireframe', 'project' => 'Website Refresh', 'employee' => 'employee@example.com', 'priority' => 'high'],
            ['title' => 'Complete app store checklist', 'project' => 'Mobile App Launch', 'employee' => 'jane@example.com', 'priority' => 'medium'],
            ['title' => 'Design customer profile page', 'project' => 'Customer Portal', 'employee' => 'alex@example.com', 'priority' => 'high'],
            ['title' => 'Map legacy customer records', 'project' => 'Data Migration', 'employee' => 'sam@example.com', 'priority' => 'medium'],
            ['title' => 'Review monthly report filters', 'project' => 'Reporting Upgrade', 'employee' => 'riley@example.com', 'priority' => 'low'],
            ['title' => 'Audit account access permissions', 'project' => 'Security Review', 'employee' => 'casey@example.com', 'priority' => 'high'],
        ];

        foreach ($tasks as $taskData) {
            $project = Project::where('name', $taskData['project'])->firstOrFail();
            $employee = User::where('email', $taskData['employee'])->firstOrFail();

            Task::firstOrCreate(['title' => $taskData['title']], [
                'project_id' => $project->id,
                'description' => 'Complete the assigned work and share an update with the team.',
                'priority' => $taskData['priority'],
                'status' => 'pending',
                'due_date' => now()->addDays(7)->toDateString(),
                'assigned_to' => $employee->id,
                'created_by' => $admin->id,
            ]);
        }
    }
}
