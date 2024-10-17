<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SendDepartmentLetterRequest extends FormRequest
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
            'audit_id' => 'required',
            'description' => 'required',
            'letter_file' => 'required|mimes:pdf,png,jpg,jpeg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'audit_id.required' => 'Please select audit',
            'letter_file.required' => 'Please upload letter',
            'letter_file.mimes' => 'Only pdf, jpg, jpeg and png formate allow',
            'letter_file.max' => 'File should be less than 2mb',
            'description.required' => 'Please enter description',
        ];
    }
}
