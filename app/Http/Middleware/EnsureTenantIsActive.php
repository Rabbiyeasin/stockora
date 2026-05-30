<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app('tenant');

        if (!$tenant) {
            return $next($request);
        }

        // Check tenant status
        if ($tenant->status === 'suspended') {
            abort(403, 'Your account has been suspended. Please contact support.');
        }

        if ($tenant->status === 'expired') {
            abort(402, 'Your subscription has expired. Please renew to continue.');
        }

        if ($tenant->status === 'trial' && $tenant->trial_ends_at && $tenant->trial_ends_at->isPast()) {
            abort(402, 'Your trial period has ended. Please subscribe to continue.');
        }

        return $next($request);
    }
}