<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tenant_name' => 'required|string|max:255',
            'tenant_email' => 'required|email|unique:tenants,email',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|min:8|confirmed',
        ]);

        $dbUsername = 'tenant_' . strtolower(str_replace(' ', '_', $request->tenant_name));
        $dbPassword = bin2hex(random_bytes(8));

        DB::beginTransaction();

        try {
            // Step 1: Create the database user
            DB::statement("CREATE USER '$dbUsername'@'%' IDENTIFIED BY '$dbPassword'");
            DB::statement("GRANT ALL PRIVILEGES ON database_name.* TO '$dbUsername'@'%'");

            // Step 2: Create the tenant
            $tenant = Tenant::create([
                'name' => $request->tenant_name,
                'email' => $request->tenant_email,
                'db_username' => $dbUsername,
                'db_password' => encrypt($dbPassword),
            ]);

            // Step 3: Create the Admin user
            $adminUser = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
                'tenant_id' => $tenant->id,
            ]);

            // Step 4: Assign Admin Role
            $adminRole = Role::where('name', 'Admin')->first();
            $adminUser->roles()->attach($adminRole);

            DB::commit();

            return response()->json([
                'message' => 'Tenant and Admin user created successfully!',
                'tenant' => $tenant,
                'admin_user' => $adminUser,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create tenant and Admin user: ' . $e->getMessage()], 500);
        }
    }
}
