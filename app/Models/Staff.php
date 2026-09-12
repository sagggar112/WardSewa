<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'palika_id',
        'ward_id',
        'role',
        'signature_image_path',
        'stamp_image_path',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
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

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ApplicationStatusLog::class);
    }

    public function isWardChair(): bool
    {
        return $this->role === 'ward_chair';
    }

    public function isSecretary(): bool
    {
        return $this->role === 'secretary';
    }

    public function isClerk(): bool
    {
        return $this->role === 'clerk';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canApproveApplications(): bool
    {
        return in_array($this->role, ['ward_chair', 'secretary', 'admin']);
    }
}
