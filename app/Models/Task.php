<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'project_id', 'created_by', 'assigned_to', 'tenant_id'];

    // A task belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // A task is created by a user
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A task is assigned to a user
    public function assigned_to_user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
