<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'client_uuid'            => 'required|exists:clients,uuid',
            'payments'             => 'required|array|min:1|max:100',
            'payments.*.beneficiary_uuid' => 'required|exists:beneficiaries,uuid',
            'payments.*.amount'    => 'required|numeric|min:0.01|max:999999999.99',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $clientUuid = $this->input('client_uuid');
            $client = Client::where('uuid', $clientUuid)->first();
            $payments = $this->input('payments', []);

            if ($clientUuid && !empty($payments)) {
                // Verificar que todos los beneficiarios pertenezcan al cliente
                $beneficiaryIds = collect($payments)->pluck('beneficiary_uuid')->unique();
                $clientBeneficiaries = \App\Models\Beneficiary::where('client_id', $client->id)
                    ->whereIn('uuid', $beneficiaryIds)
                    ->pluck('uuid');

                $invalidBeneficiaries = $beneficiaryIds->diff($clientBeneficiaries);

                if ($invalidBeneficiaries->isNotEmpty()) {
                    $validator->errors()->add(
                        'payments',
                        'Los beneficiarios con IDs: ' . $invalidBeneficiaries->implode(', ') . ' no pertenecen al cliente especificado'
                    );
                }
            }
        });
    }
}
