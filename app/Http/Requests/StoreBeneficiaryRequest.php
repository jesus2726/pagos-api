<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeneficiaryRequest extends FormRequest
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
            'client_uuid'     => 'required|exists:clients,uuid',
            'name'            => 'required|string|max:150|min:2',
            'email'           => 'nullable|email|max:150|unique:beneficiaries,email,NULL,id,deleted_at,NULL',
            'account_number'  => 'required|string|max:50|min:1',
            'bank_name'       => 'required|string|max:100|min:2',
            'identification'  => 'nullable|string|max:50',
        ];
    }
}
