<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesReturnRequest extends FormRequest
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
            'medicine'       => 'required|array|min:1',
            'medicine.*'     => 'required|exists:medicines,id',
            'expire_date'    => 'required|array',
            'expire_date.*'  => 'required|date',
            'qty'            => 'required|array',
            'qty.*'          => 'required|numeric|min:0.01',
            'price'          => 'required|array',
            'price.*'        => 'required|numeric|min:0',
            'total'          => 'required|array',
            'total.*'        => 'required|numeric|min:0',
            'customer'       => 'nullable|array',
            'customer.*'     => 'nullable|exists:customers,id',
            'return_reason'  => 'nullable|array',
            'return_reason.*'=> 'nullable|string|max:500',
        ];
    }
}
