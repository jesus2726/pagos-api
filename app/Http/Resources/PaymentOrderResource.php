<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentOrderResource extends JsonResource
{
    public function toArray($request)
    {
        if (!isset($this->id)) {
            return [];
        }
        return [
            'uuid'         => $this->uuid,
            'total_amount' => $this->total_amount,
            'status'       => $this->status,
            'created_at'   => $this->created_at,
            'payments'     => PaymentResource::collection($this->whenLoaded('payments')),
            'client'       => $this->whenLoaded('client', function () {
                return [
                    'name' => $this->client->name,
                    'email' => $this->client->email,
                    'status' => $this->client->status,
                    'uuid' => $this->client->uuid,
                    'created_at' => $this->client->created_at,
                ];
            }),
        ];
    }
}
