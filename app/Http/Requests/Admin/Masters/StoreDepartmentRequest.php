<?php

namespace App\Http\Requests\Admin\Masters;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
                'name' => "required|unique:departments,name,$this->edit_model_id,id,deleted_at,NULL",
                'initial' => 'required',
                'is_audit' => 'required',
            ];
        } else {
            return [
                'name' => 'required|unique:departments,name,NULL,NULL,deleted_at,NULL',
                'initial' => 'required',
                'is_audit' => 'required',
            ];
        }
    }
}
