<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        if (!isset($this->id)) {
            return [];
        }
        return [
            'uuid'            => $this->uuid,
            'amount'          => $this->amount,
            'status'          => $this->status,
            'beneficiary'     => $this->whenLoaded('beneficiary', function () {
                return [
                    'name' => $this->beneficiary->name,
                    'email' => $this->beneficiary->email,
                    'account_number' => $this->beneficiary->account_number,
                    'bank_name' => $this->beneficiary->bank_name,
                    'identification' => $this->beneficiary->identification,
                    'uuid' => $this->beneficiary->uuid,
                    'created_at' => $this->beneficiary->created_at?->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
