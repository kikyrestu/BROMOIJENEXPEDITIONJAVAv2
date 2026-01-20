<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryLog extends Model
{
    protected $fillable = [
        'package_id',
        'ip_address',
        'user_agent',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'referer_url',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
