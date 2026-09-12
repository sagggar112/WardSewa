<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'palika_id',
        'ward_id',
        'staff_id',
        'title',
        'content',
        'category',
        'attachment_path',
        'is_pinned',
        'published_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function palika(): BelongsTo
    {
        return $this->belongsTo(Palika::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }

    public function scopeForWardOrPalika(Builder $query, int $palikaId, ?int $wardId = null): Builder
    {
        return $query->where('palika_id', $palikaId)
            ->where(function ($q) use ($wardId) {
                $q->whereNull('ward_id');
                if ($wardId) {
                    $q->orWhere('ward_id', $wardId);
                }
            });
    }
}
