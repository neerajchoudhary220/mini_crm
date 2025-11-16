<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3'],
            'email' => ['nullable', 'email', 'unique:contacts,email'],
            'phone' => ['nullable', 'digits_between:8,15', 'unique:contacts,phone'],
            'gender' => ['nullable', 'in:male,female,other'],
            'profile_image' => ['nullable', 'image', 'max:2048'], //Max profile image up to 2mb
            'document' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:4096']
        ];
    }
}
