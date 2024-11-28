<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'email',
        'db_username',
        'db_password'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
