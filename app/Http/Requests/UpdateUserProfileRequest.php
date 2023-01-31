<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
        $username = request('username');

        $row = User::where('username', $username)->firstOrFail();

        return [
            //
            'name' => 'required|string|max:255',
            'picture' => 'nullable|image',
            // 'username' => 'required|string|max:100|alpha_num|unique:users',
            'email' => 'required|string|max:255|email|unique:users,email,' . $row->id,
            'password' => 'nullable|string|min:6',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nama',
        ];
    }
}
