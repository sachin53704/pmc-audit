<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'role' => 'required',
            'first_name' => 'required|max:100',
            'middle_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'gender' => 'required|max:100|in:male,female,others',
            'email' => 'required|unique:users,email|email',
            'mobile' => 'required|unique:users,mobile|digits:10',
            'auditor_no' => 'required_if:role,4|max:50',
            // 'home_department_id' => 'required_if:role,4,7|max:50',
            'department_id' => 'required_if:role,3,5,6,8,9|max:50',
            'username' => 'required|max:100|unique:users,username',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ];
    }
}
