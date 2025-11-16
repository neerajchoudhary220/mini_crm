<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MergeRequest extends FormRequest
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
            'primary_id' => 'required|integer|exists:contacts,id',
            'secondary_id' => 'required|integer|exists:contacts,id',
            'master' => 'required|in:primary,secondary',
            'policy' => 'nullable|in:keep_master,append',
        ];
    }
}
