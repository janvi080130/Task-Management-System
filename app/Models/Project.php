<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function projects() { return $this->hasMany(Project::class, 'created_by'); }
    public function assignedTasks() { return $this->hasMany(Task::class, 'assigned_to'); }
    public function createdTasks() { return $this->hasMany(Task::class, 'created_by'); }

    public function isAdmin() { return $this->role === 'admin'; }
}
