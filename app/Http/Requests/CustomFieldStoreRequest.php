<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomFieldStoreRequest extends FormRequest
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
            'field_label' => ['required', 'min:3'],
            'field_name' => ['required', 'min:3', 'unique:contact_custom_fields,field_name'],
            'field_type' => ['required', 'in:text,textarea,date,email'],
            'options' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->field_type === 'select' && !empty($value)) {
                        if (!preg_match('/^[^,\s][^,]*(?:,[^,\s][^,]*)*$/', $value)) {
                            return $fail('Options must be comma separated without empty values.');
                        }
                    }
                }
            ],
            'custom' => ['nullable', 'array']
        ];
    }
}
