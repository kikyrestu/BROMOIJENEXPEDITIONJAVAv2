<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotspot extends Model
{
    protected $fillable = [
        'page_id',
        'destination_id',
        'x_coordinate',
        'y_coordinate',
        'label_custom',
    ];

    protected $casts = [
        'x_coordinate' => 'decimal:2',
        'y_coordinate' => 'decimal:2',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
