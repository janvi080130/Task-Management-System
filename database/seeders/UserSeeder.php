<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'employee@example.com'], [
            'name' => 'John Employee',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'jane@example.com'], [
            'name' => 'Jane Employee',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        $employees = [
            ['name' => 'Alex Morgan', 'email' => 'alex@example.com'],
            ['name' => 'Sam Taylor', 'email' => 'sam@example.com'],
            ['name' => 'Riley Jordan', 'email' => 'riley@example.com'],
            ['name' => 'Casey Lee', 'email' => 'casey@example.com'],
            ['name' => 'Morgan Quinn', 'email' => 'morgan@example.com'],
        ];

        foreach ($employees as $employee) {
            User::updateOrCreate(['email' => $employee['email']], [
                ...$employee,
                'password' => bcrypt('password'),
                'role' => 'employee',
                'email_verified_at' => now(),
            ]);
        }
    }
}
