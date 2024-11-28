<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array', // Array of role names
            'roles.*' => 'exists:roles,name',
        ]);

        $adminUser = auth()->user();

        if (!$adminUser || !$adminUser->roles->contains('name', 'Admin')) {
            return response()->json(['error' => 'Only Admins can create users'], 403);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $adminUser->tenant_id,
        ]);

        $roles = Role::whereIn('name', $request->roles)->get();
        $user->roles()->attach($roles);

        return response()->json([
            'message' => 'User created successfully!',
            'user' => $user,
        ], 201);
    }
}
