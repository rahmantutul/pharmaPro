<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
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
            'medicine.*' => 'required',
            'supplier'   => 'required',
            'qty.*'      => 'required|numeric|min:1',
            'price.*'    => 'required|numeric|min:1',
            'total.*'    => 'required|numeric|min:1',
            'grandTotal' => 'required|numeric|min:1',
        ];
    }
}
