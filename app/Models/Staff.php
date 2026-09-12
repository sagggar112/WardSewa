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
        'district_id',
        'palika_id',
        'ward_id',
        'role',
        'designation',
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

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
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

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper Methods (4-Tier Hierarchy)
    |--------------------------------------------------------------------------
    */

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->role === 'admin';
    }

    public function isDistrictAdmin(): bool
    {
        return $this->role === 'district_admin';
    }

    public function isLocalGovtAdmin(): bool
    {
        return $this->role === 'local_government_admin';
    }

    public function isWardAdmin(): bool
    {
        return in_array($this->role, ['ward_admin', 'ward_chair', 'secretary', 'clerk']);
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

    public function canApproveApplications(): bool
    {
        return in_array($this->role, ['super_admin', 'ward_chair', 'secretary', 'ward_admin', 'admin']);
    }

    public function getRoleTitleAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Administrator (सुपर एडमिन)',
            'district_admin' => 'District Administrator (जिल्ला प्रशासक)',
            'local_government_admin' => 'Local Government Administrator (पालिका प्रशासक)',
            'ward_admin' => 'Ward Administrator (वडा प्रशासक)',
            'ward_chair' => 'Ward Chairperson (वडा अध्यक्ष)',
            'secretary' => 'Ward Secretary (वडा सचिव)',
            'clerk' => 'Front Desk Clerk (वडा सहायक)',
            default => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }

    public function getScopeDescriptionAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'समग्र प्रणाली (System-Wide)';
        }

        if ($this->isDistrictAdmin()) {
            return ($this->district ? $this->district->name_ne : 'जिल्ला') . ' कार्यक्षेत्र';
        }

        if ($this->isLocalGovtAdmin()) {
            return ($this->palika ? $this->palika->name_ne : 'पालिका') . ' (समग्र वडा कार्यक्षेत्र)';
        }

        if ($this->ward) {
            $palikaName = $this->ward->palika ? $this->ward->palika->name_ne : '';
            return "{$palikaName} - वडा नं. {$this->ward->ward_number}";
        }

        return 'वडा कार्यक्षेत्र';
    }
}
