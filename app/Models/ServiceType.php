<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ne',
        'code',
        'category',
        'fee',
        'turnaround_days',
        'required_documents',
        'form_fields',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fee' => 'decimal:2',
            'turnaround_days' => 'integer',
            'required_documents' => 'array',
            'form_fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
