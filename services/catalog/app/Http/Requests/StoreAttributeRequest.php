<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attributes,slug',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя атрибута обязательно для заполнения',
            'name.max' => 'Имя атрибута не должно превышать 255 символов',
            'slug.required' => 'Slug атрибута обязательно для заполнения',
            'slug.max' => 'Slug атрибута не должно превышать 255 символов',
            'slug.unique' => 'Атрибут с таким slug уже существует'
        ];
    }
}
