<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'db_username', 'db_password'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
