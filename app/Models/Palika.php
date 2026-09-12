<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Palika extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'name_en',
        'name_ne',
        'type',
        'code',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }
}
