<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'palika_id',
        'ward_number',
        'office_address',
        'office_phone',
        'office_email',
    ];

    public function palika(): BelongsTo
    {
        return $this->belongsTo(Palika::class);
    }

    public function citizens(): HasMany
    {
        return $this->hasMany(Citizen::class);
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
