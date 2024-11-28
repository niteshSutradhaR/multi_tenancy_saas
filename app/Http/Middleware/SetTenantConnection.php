<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class SetTenantConnection
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Ensure the user is authenticated and has a tenant
        if ($user && $user->tenant) {
            $tenant = $user->tenant;

            // Dynamically set the tenant database connection
            Config::set('database.connections.tenant.database', $tenant->db_name);
            Config::set('database.connections.tenant.username', $tenant->db_username);
            Config::set('database.connections.tenant.password', decrypt($tenant->db_password));

            // Switch to tenant connection
            DB::setDefaultConnection('tenant');

            return $next($request);
        }

        return response()->json(['error' => 'Tenant not found'], 403);
    }
}

