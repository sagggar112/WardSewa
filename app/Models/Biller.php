<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Biller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ne',
        'category',
        'code',
        'logo_url',
        'api_config',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'api_config' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function billAccounts(): HasMany
    {
        return $this->hasMany(CitizenBillAccount::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }
}
