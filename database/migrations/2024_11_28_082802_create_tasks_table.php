<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('tenant_id')->constrained('tenants'); // Foreign key to tenants table
            $table->string('title'); // Task title
            $table->text('description')->nullable(); // Task description
            $table->enum('status', ['pending', 'in_progress', 'completed']); // Task status
            $table->foreignId('project_id')->constrained('projects'); // Foreign key to projects table
            $table->foreignId('created_by')->constrained('users'); // Foreign key to users table
            $table->foreignId('assigned_to')->constrained('users'); // Foreign key to users table
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
