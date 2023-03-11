<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'numeric',
                'min:2',
                Rule::unique('products')->ignore($this->product),
            ],
            'name' => [
                'required',
                'string',
                'min:2',
            ],
            'slug' => [
                'nullable',
                'string',
                'min:2',
                'alpha_dash',
                Rule::unique('products')->ignore($this->product),
            ],
        ];
    }
}
