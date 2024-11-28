<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('tenant_id')->constrained('tenants'); // Foreign key to tenants table
            $table->string('name'); // Project name
            $table->text('description')->nullable(); // Project description
            $table->foreignId('created_by')->constrained('users'); // Foreign key to users table
            $table->foreignId('assigned_to')->constrained('users'); // Foreign key to users table
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
