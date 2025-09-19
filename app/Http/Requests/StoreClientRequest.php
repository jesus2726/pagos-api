<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'name'    => 'required|string|max:150|min:2',
            'email'   => 'required|email|max:150|unique:clients,email,NULL,id,deleted_at,NULL',
            'balance' => 'nullable|numeric|min:0|max:999999999.99',
        ];
    }
}
