<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReviewToken extends Model
{
    protected $fillable = [
        'token',
        'expires_at',
        'used_at',
        'created_by',
        'label',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public static function generate(int $createdBy, ?string $label = null, int $expiryDays = 30): static
    {
        return static::create([
            'token' => Str::random(64),
            'expires_at' => now()->addDays($expiryDays),
            'created_by' => $createdBy,
            'label' => $label,
        ]);
    }

    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }

    public function markUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('used_at')->where('expires_at', '>', now());
    }

    public function scopeUsed($query)
    {
        return $query->whereNotNull('used_at');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class, 'review_token_id');
    }

    public function getReviewUrlAttribute(): string
    {
        return route('client.review.create', ['token' => $this->token]);
    }
}
