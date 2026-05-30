<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenantByClientId
{
    public function handle(Request $request, Closure $next): Response
    {
        // Extract client_id from route parameter
        $clientId = $request->route('client_id');

        if (!$clientId) {
            // Not a tenant route
            return $next($request);
        }

        // Validate format (exactly 6 digits)
        if (!preg_match('/^\d{6}$/', $clientId)) {
            abort(404, 'Invalid client ID format');
        }

        // Find tenant
        $tenant = Tenant::where('client_id', $clientId)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Check if authenticated user belongs to this tenant
        if (auth()->check() && auth()->user()->tenant_id !== $tenant->id) {
            abort(403, 'You do not have access to this client');
        }

        // Set tenant in application context
        app()->instance('tenant', $tenant);
        config(['app.current_tenant_id' => $tenant->id]);

        return $next($request);
    }
}