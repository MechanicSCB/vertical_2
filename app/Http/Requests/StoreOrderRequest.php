<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
            ],
            'phone' => [
                'required',
                //'regex:/^([0-9\s\-\+\(\)]*)$/',
                //'digits:11',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            // add cart rules
        ];
    }
}
