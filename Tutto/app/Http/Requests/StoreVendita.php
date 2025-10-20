<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendita extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'agente' => 'required|string|max:50',
            'importo' => 'required|numeric|min:1|decimal:0,2',
            'data_vendita' => 'required|date|before_or_equal:today'
        ];
    }

    public function messages(): array
    {
        return [
            'data_vendita.before_or_equal' => 'La data della vendita non può essere futura'
        ];
    }
}
