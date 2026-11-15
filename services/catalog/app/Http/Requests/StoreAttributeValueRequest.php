<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttributeValueRequest extends FormRequest
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
            'attribute_id' => 'required|integer|exists:attributes,id',
            'value' => ['required', 'string', 'max:255',
                Rule::unique('attribute_values', 'value')->where(function ($query) {
                    return $query->where('attribute_id', $this->attribute_id);
                })]
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'ID атрибута обязательно для заполнения',
            'attribute_id.integer' => 'ID атрибута должно быть числом',
            'attribute_id.exists' => 'Атрибута с таким ID не существует',
            'value.required' => 'Значение атрибута обязательно для заполнения',
            'value.string' => 'Значение атрибута должно быть в строковом формате',
            'value.max' => 'Максимально допустимая длина значения атрибута 255',
            'value.unique' => 'Для этого атрибута уже существует такое значение'
        ];
    }
}
