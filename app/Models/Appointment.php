<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number',
        'citizen_id',
        'application_id',
        'palika_id',
        'ward_id',
        'service_type_id',
        'appointment_date',
        'time_slot',
        'status',
        'purpose',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
        ];
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function palika(): BelongsTo
    {
        return $this->belongsTo(Palika::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function scopeForWard(Builder $query, int $wardId): Builder
    {
        return $query->where('ward_id', $wardId);
    }

    public function scopeForPalika(Builder $query, int $palikaId): Builder
    {
        return $query->where('palika_id', $palikaId);
    }

    public function scopeForDistrict(Builder $query, int $districtId): Builder
    {
        return $query->whereHas('palika', function ($q) use ($districtId) {
            $q->where('district_id', $districtId);
        });
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['scheduled', 'confirmed', 'rescheduled']);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->where('appointment_date', now()->toDateString());
    }
}
