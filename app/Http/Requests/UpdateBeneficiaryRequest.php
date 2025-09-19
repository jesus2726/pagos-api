<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBeneficiaryRequest extends FormRequest
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
            'name'            => 'sometimes|required|string|max:150|min:2',
            'email'           => 'sometimes|nullable|email|max:150',
            'account_number'  => 'sometimes|required|string|max:50|min:1',
            'bank_name'       => 'sometimes|required|string|max:100|min:2',
            'identification'  => 'sometimes|nullable|string|max:50',
        ];
    }
}
