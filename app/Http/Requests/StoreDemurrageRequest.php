<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemurrageRequest extends FormRequest
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
            'medicineId'     => 'required|exists:medicines,id',
            'demurrage_date' => 'required|date',
            'price'          => 'required|numeric|min:0',
            'quantity'       => 'required|numeric|min:1',
            'total'          => 'required|numeric|min:0',
        ];
    }
}
