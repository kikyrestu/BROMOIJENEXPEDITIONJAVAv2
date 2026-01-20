<?php

namespace App\Filament\Traits;

trait HasPermissionCheck
{
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Bypass for legacy admin role (backward compatibility)
        if ($user->role === 'admin') {
            return true;
        }
        
        // Check spatie permission based on resource name
        $resourceName = class_basename(static::class);
        $resourceName = str_replace('Resource', '', $resourceName);
        $permissionName = 'view_' . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $resourceName));
        
        return $user->can($permissionName);
    }
}
