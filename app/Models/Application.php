<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'citizen_id',
        'service_type_id',
        'palika_id',
        'ward_id',
        'form_data',
        'status',
        'remarks',
        'rejection_reason',
        'payment_status',
        'payment_amount',
        'payment_method',
        'khalti_transaction_id',
        'verified_by',
        'approved_by',
        'approved_at',
        'qr_code_token',
        'certificate_path',
    ];

    protected function casts(): array
    {
        return [
            'form_data' => 'array',
            'payment_amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function palika(): BelongsTo
    {
        return $this->belongsTo(Palika::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'verified_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ApplicationStatusLog::class)->orderBy('created_at', 'desc');
    }

    public function vitalRecord(): HasOne
    {
        return $this->hasOne(VitalRecord::class);
    }

    // Ward-level scoping for multi-tenancy
    public function scopeForWard(Builder $query, int $wardId): Builder
    {
        return $query->where('ward_id', $wardId);
    }

    public function scopeForPalika(Builder $query, int $palikaId): Builder
    {
        return $query->where('palika_id', $palikaId);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' || $this->payment_status === 'waived';
    }
}
