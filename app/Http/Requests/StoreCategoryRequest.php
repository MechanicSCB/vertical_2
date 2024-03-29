<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'min:2',
            ],
            'slug' => [
                'nullable',
                'string',
                'min:2',
                'alpha_dash',
                Rule::unique('categories')->ignore($this->category),
            ],
            //'parent_nodes.*.id' => [
            //    'exists:category_nodes',
            //],
            //'parent_nodes.*.order_inside' => [
            //    'nullable',
            //    'numeric',
            //],
            //'parent_id' => [
            //    'nullable',
            //    'exists:categories',
            //],
        ];
    }
}
