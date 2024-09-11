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
            'amount' => 'required_if:audit_para_value, 1',
            'subject' => 'required',
            'documents' => 'nullable|mimes:docx,doc,xlsx,xls,pdf',
            'sub_unit' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'objection_no.required' => 'Please enter audit para no',
            'entry_date.required' => 'Please select entry date',
            'department_id.required' => 'Please select department',
            'zone_id.required' => 'Please select zone',
            'from_year.required' => 'Please select from year',
            'to_year.required' => 'Please select to year',
            'audit_type_id.required' => 'Please select audit type',
            'severity_id.required' => 'Please select severity',
            'audit_para_category_id.required' => 'Please select audit para category',
            'amount.required' => 'Please enter amount',
            'subject.required' => 'Please enter subject',
            'documents.mimes' => 'Only docx, doc, xlsx, xls and pdf file is allowed',
            'sub_unit.required' => 'Please enter sub unit',
            'description.required' => 'Please enter description'
        ];
    }
}
