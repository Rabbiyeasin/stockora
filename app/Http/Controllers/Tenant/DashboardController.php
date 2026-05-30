<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        
        return Inertia::render('Tenant/Dashboard', [
            'tenant' => [
                'id' => $tenant->id,
                'client_id' => $tenant->client_id,
                'business_name' => $tenant->business_name,
                'status' => $tenant->status,
                'trial_ends_at' => $tenant->trial_ends_at?->format('M d, Y'),
                'is_on_trial' => $tenant->isOnTrial(),
            ],
            'user' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'roles' => auth()->user()->roles->pluck('name'),
            ],
        ]);
    }
}