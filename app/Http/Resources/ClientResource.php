<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        if (!isset($this->id)) {
            return [];
        }
        return [
            'uuid'       => $this->uuid,
            'name'       => $this->name,
            'email'      => $this->email,
            'balance'    => $this->balance,
            'status'     => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'beneficiaries' => BeneficiaryResource::collection($this->whenLoaded('beneficiaries')),
            'paymentOrders' => $this->whenLoaded('paymentOrders', function () {
                return $this->paymentOrders->map(function ($paymentOrder) {
                    return [
                        'status' => $paymentOrder->status,
                        'total_amount' => $paymentOrder->total_amount,
                        'uuid' => $paymentOrder->uuid,
                        'created_at' => $paymentOrder->created_at?->format('Y-m-d H:i:s'),
                    ];
                });
            }),
        ];
    }
}
