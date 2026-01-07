<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'invoice_date'      => 'required|date',
            'invoice_no'        => 'required|string|unique:sales,invoice_no',
            'medicineId'        => 'required|array|min:1',
            'medicineId.*'      => 'required|exists:medicines,id',
            'expire_date'       => 'required|array',
            'expire_date.*'     => 'required|date|after:today',
            'price'             => 'required|array',
            'price.*'           => 'required|numeric|min:0',
            'qty'               => 'required|array',
            'qty.*'             => 'required|numeric|min:0.01',
            'subtotal'          => 'required|array',
            'subtotal.*'        => 'required|numeric|min:0',
            'discount'          => 'nullable|array',
            'discount.*'        => 'nullable|numeric|min:0',
            'total'             => 'required|array',
            'total.*'           => 'required|numeric|min:0',
            'grand_total'       => 'required|numeric|min:0',
            'invoice_discount'  => 'nullable|numeric|min:0',
            'discount_type'     => 'nullable|in:1,2', // 1=Percentage, 2=Fixed
            'payable_total'     => 'required|numeric|min:0',
            'paid_amount'       => 'required|numeric|min:0',
            'due_amount'        => 'required|numeric|min:0',
            'paymentId'         => 'required|exists:payment_methods,id',
            'customerId'        => 'nullable|exists:customers,id',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->due_amount > 0 && !$this->customerId) {
                $validator->errors()->add('customerId', 'Customer is required when there is a due amount');
            }
        });
    }
}
