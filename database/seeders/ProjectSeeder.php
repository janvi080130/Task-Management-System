<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $projects = [
            ['name' => 'Website Refresh', 'description' => 'Improvements to the company website.'],
            ['name' => 'Mobile App Launch', 'description' => 'Prepare the new mobile app for release.'],
            ['name' => 'Customer Portal', 'description' => 'Build a self-service portal for customers.'],
            ['name' => 'Data Migration', 'description' => 'Move legacy records into the new platform.'],
            ['name' => 'Reporting Upgrade', 'description' => 'Improve reporting and analytics tools.'],
            ['name' => 'Security Review', 'description' => 'Review and strengthen application security.'],
        ];

        foreach ($projects as $project) {
            Project::firstOrCreate(
                ['name' => $project['name']],
                [...$project, 'created_by' => $admin->id],
            );
        }
    }
}
