<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class SetTenantConnection
{
    public function handle($request, Closure $next)
    {
        // Get tenant ID from session
        $tenantId = session('tenant_id');

        if (!$tenantId) {
            return response()->json(['error' => 'Tenant not identified'], 403);
        }

        // Fetch tenant details
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Set the database connection dynamically (optional)
        config([
            'database.connections.tenant' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE'),
                'username' => $tenant->db_username,
                'password' => decrypt($tenant->db_password),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ],
        ]);

        // Switch to tenant connection
        DB::setDefaultConnection('tenant');

        return $next($request);
    }
}

