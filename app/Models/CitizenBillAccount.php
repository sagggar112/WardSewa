<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitizenBillAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id',
        'biller_id',
        'consumer_id',
        'account_holder_name',
        'nickname',
    ];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function biller(): BelongsTo
    {
        return $this->belongsTo(Biller::class);
    }
}
