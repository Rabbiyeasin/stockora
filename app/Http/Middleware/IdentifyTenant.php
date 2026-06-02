<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientId = $request->route('client_id');

        if (!$clientId) {
            abort(404, 'Client ID not provided');
        }

        $tenant = Tenant::where('client_id', $clientId)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Verify authenticated user belongs to this tenant
        if ($request->user()->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this tenant');
        }

        // Check tenant status
        if ($tenant->status === 'suspended') {
            abort(403, 'Account suspended. Please contact support.');
        }

        if ($tenant->status === 'cancelled') {
            abort(403, 'Account cancelled. Please renew your subscription.');
        }

        // Check trial expiration
        if ($tenant->status === 'trial' && $tenant->isTrialExpired()) {
            return redirect()->route('subscription.expired')
                ->with('error', 'Your trial has expired. Please subscribe to continue.');
        }

        // Store tenant in request for easy access
        $request->merge(['tenant' => $tenant]);
        app()->instance('tenant', $tenant);

        return $next($request);
    }
}