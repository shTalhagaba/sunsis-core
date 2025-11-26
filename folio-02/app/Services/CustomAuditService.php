<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Models\Audit;

class CustomAuditService
{
    public function create(array $auditDetails, Model $auditable, User $user)
    {
        $audit = new Audit([
            'event' => $auditDetails['event'] ?? 'created',
            'old_values' => $auditDetails['old_values'] ?? [],
            'new_values' => $auditDetails['new_values'] ?? [],
            'url' => $auditDetails['url'] ?? request()->fullUrl(),
            'ip_address' => $auditDetails['ip_address'] ?? request()->getClientIp(),
            'user_agent' => $auditDetails['user_agent'] ?? request()->userAgent(),
        ]);
        $audit->auditable()->associate($auditable);
        $audit->user()->associate($user ?? auth()->user());
        $audit->save();

        return $audit;
    }
}