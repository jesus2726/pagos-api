<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'total_amount',
        'status',
        'uuid',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transactionAudit()
    {
        return $this->morphOne(TransactionAudit::class, 'related');
    }

    // Scopes para optimización
    public function scopeWithRelations($query)
    {
        return $query->with(['client', 'payments.beneficiary']);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'successful');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
