<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseInvoiceRequest extends FormRequest
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
            'medicine'       => 'required|array',
            'expire_date'    => 'required|array',
            'supplier'       => 'required',
            'qty'            => 'required|array',
            'price'          => 'required|array',
            'total'          => 'required|array',
            'discount_type'  => 'nullable|in:2,1',
            'discount'       => 'nullable|numeric|min:0',
            'grandTotal'     => 'required|numeric|min:0',
            'paidAmount'     => 'required|numeric|min:0',
            'dueAmount'      => 'required|numeric|min:0',
        ];
    }
}
