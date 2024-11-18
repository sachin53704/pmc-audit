<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SequenceRequest extends FormRequest
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
        if ($this->edit_model_id) {
            return [
                'department_id' => 'required',
                'fiscal_year_id' => 'required',
                'serial_no' => 'required',
                'status' => 'required'
            ];
        } else {
            return [
                'department_id' => 'required',
                'fiscal_year_id' => 'required',
                'serial_no' => 'required',
                'status' => 'required'
            ];
        }
    }

    public function messages()
    {
        return [
            'department_id.required' => 'Please select department',
            'fiscal_year_id.required' => 'Please select financial year',
            'serial_no.required' => 'Please enter serial no',
            'status.required' => 'Please select status',
        ];
    }
}
