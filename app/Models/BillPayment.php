<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'citizen_id',
        'biller_id',
        'consumer_id',
        'amount',
        'service_charge',
        'total_amount',
        'payment_gateway',
        'gateway_ref_id',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function biller(): BelongsTo
    {
        return $this->belongsTo(Biller::class);
    }
}
