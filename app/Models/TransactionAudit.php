<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'transaction_type',
        'client_id',
        'related_id',
        'related_type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function related()
    {
        return $this->morphTo('related', 'related_type', 'related_id');
    }
}
