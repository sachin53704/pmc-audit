<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiaryRequest extends FormRequest
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
            'work' => 'required',
            'date' => 'required',
            'department_id' => 'required',
            'working_day_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'work.required' => 'Please enter message',
            'date.required' => 'Please select date'
        ];
    }
}
