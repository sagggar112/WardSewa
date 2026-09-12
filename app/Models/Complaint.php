<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'citizen_id',
        'palika_id',
        'ward_id',
        'category',
        'subject',
        'description',
        'location',
        'is_anonymous',
        'anonymous_contact',
        'status',
        'priority',
        'response',
        'assigned_to',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function palika(): BelongsTo
    {
        return $this->belongsTo(Palika::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }
}
