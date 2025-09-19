<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
        $clientUuid = $this->route('uuid');


        return [
            'name'    => 'sometimes|required|string|max:150|min:2',
            'email'   => [
                'sometimes',
                'required',
                'email',
                'max:150',
                Rule::unique('clients', 'email')->ignore($clientUuid)
            ],
            'balance' => 'sometimes|nullable|numeric|min:0|max:999999999.99',
        ];
    }
}
