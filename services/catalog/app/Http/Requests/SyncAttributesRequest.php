<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SyncAttributesRequest extends FormRequest
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
            'attributes' => 'nullable|array',
            'attributes.*' => 'integer|exists:attributes,id'
        ];
    }

    public function messages(): array
    {
        return [
            'attributes.array' => 'Атрибуты должны быть в формате массива',
            'attributes.*.integer' => 'ID атрибута должен быть числом',
            'attributes.*.exists' => 'Атрибута с таким id не существует'
        ];
    }
}
