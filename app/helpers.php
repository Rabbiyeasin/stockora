<?php

if (!function_exists('tenant')) {
    /**
     * Get current tenant instance or property
     */
    function tenant($key = null)
    {
        $tenant = app('tenant');

        if (is_null($key)) {
            return $tenant;
        }

        return data_get($tenant, $key);
    }
}

if (!function_exists('tenant_id')) {
    /**
     * Get current tenant ID
     */
    function tenant_id()
    {
        return tenant('id');
    }
}