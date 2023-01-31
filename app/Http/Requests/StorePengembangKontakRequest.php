<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengembangKontakRequest extends FormRequest
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
        return [
            //
            'nama' => 'required|string|max:255',
            'handphone' => 'required|numeric|unique:pengembang_kontaks',
            'email' => 'required|string|max:255|email|unique:pengembang_kontaks',
            'posisi' => 'nullable|string|max:255',
            'pengembang_id' => 'required|string',
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
            'pengembang_id' => 'pengembang',
        ];
    }
}
