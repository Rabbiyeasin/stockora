<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request, string $clientId): Response
    {
        $tenant = $request->user()->tenant;

        return Inertia::render('Dashboard', [
            'tenant' => [
                'name' => $tenant->name,
                'status' => $tenant->status,
                'trial_ends_at' => $tenant->trial_ends_at,
                'subscription_expires_at' => $tenant->subscription_expires_at,
            ],
            'clientId' => $clientId,
        ]);
    }
}