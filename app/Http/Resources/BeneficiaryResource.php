<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiaryResource extends JsonResource
{
    public function toArray($request)
    {
        if (!isset($this->uuid)) {
            return [];
        }
        return [
            'uuid'            => $this->uuid,
            'name'            => $this->name,
            'email'           => $this->email,
            'account_number'  => $this->account_number,
            'bank_name'       => $this->bank_name,
            'identification'  => $this->identification,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'client'          => $this->whenLoaded('client', function () {
                return [
                    'name' => $this->client->name,
                    'email' => $this->client->email,
                    'uuid' => $this->client->uuid,
                    'created_at' => $this->client->created_at?->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
