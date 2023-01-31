<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->route('user')->id ?? NULL;

        return [
            //
            'name' => 'required|string|max:255',
            'picture' => 'nullable|image',
            // 'username' => 'required|string|max:100|alpha_num|unique:users',
            'email' => 'required|string|max:255|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'nama',
        ];
    }
}
