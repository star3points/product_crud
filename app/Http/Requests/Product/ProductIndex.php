<?php

namespace App\Http\Requests\Product;

use App\Rules\PriceRange;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndex extends FormRequest
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
            'name' => 'string',
            'category_id' => 'exists:App\Models\Category,id',
            'category_name' => 'string',
            'prices' => [new PriceRange()],
            'is_published' => 'boolean'
        ];
    }
}
