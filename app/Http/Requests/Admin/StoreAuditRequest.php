<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditRequest extends FormRequest
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
            'department_id' => 'required',
            'date' => 'required',
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'description' => 'required',
            'remark' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => 'Please select department',
            'date.required' => 'Please select date',
            'file.required' => 'Please upload file',
            'file.mimes' => 'Only pdf, jpg, jpeg and png formate allow',
            'file.max' => 'File should be less than 2mb',
            'description.required' => 'Please enter description',
            'remark.required' => 'Please enter remark',
        ];
    }
}
