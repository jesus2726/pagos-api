<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'balance',
        'uuid',
    ];

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function paymentOrders()
    {
        return $this->hasMany(PaymentOrder::class);
    }

    public function transactionAudits()
    {
        return $this->hasMany(TransactionAudit::class);
    }

    // Scopes para optimización
    public function scopeWithRelations($query)
    {
        return $query->with(['beneficiaries', 'paymentOrders.payments.beneficiary']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
