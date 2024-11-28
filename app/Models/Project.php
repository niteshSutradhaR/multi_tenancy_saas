<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'created_by', 'assigned_to', 'tenant_id'];

    // A project belongs to a tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // A project has many tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
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
