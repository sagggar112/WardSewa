<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'palika_id',
        'ward_id',
        'type',
        'registration_number',
        'event_date_bs',
        'event_date_ad',
        'person_name',
        'details',
        'certificate_issued_at',
        'issued_by',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'event_date_ad' => 'date',
            'certificate_issued_at' => 'datetime',
        ];
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

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'issued_by');
    }
}
