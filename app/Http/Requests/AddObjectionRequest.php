<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddObjectionRequest extends FormRequest
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
            'objection_no' => 'required',
            'entry_date' => 'required',
            'department_id' => 'required',
            'zone_id' => 'required',
            'from_year' => 'required',
            'to_year' => 'required',
            'audit_type_id' => 'required',
            'severity_id' => 'required',
            'audit_para_category_id' => 'required',
            'amount' => 'required|required_if:audit_para_category_id,1',
            'subject' => 'required',
            'document' => 'nullable|mimes:docx,doc,xlsx,xls,pdf',
            'sub_unit' => 'required',
            'description' => 'required'
        ];
    }
}
