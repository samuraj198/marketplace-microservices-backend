<?php

namespace App\Http\Requests;

use App\Models\AttributeValue;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeValueRequest extends FormRequest
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
        $attributeValueID = $this->route('value')->id ?? $this->route('value');
        $attributeID = $this->attribute_id ?? AttributeValue::find($attributeValueID)?->attribute_id;

        return [
            'attribute_id' => 'sometimes|required|integer|exists:attributes,id',
            'value' => ['sometimes', 'required', 'string', 'max:255',
                Rule::unique('attribute_values', 'value')->where(fn ($query) =>
                $query->where('attribute_id', $attributeID))->ignore($attributeValueID)]
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
