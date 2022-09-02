<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengelolaKontakRequest extends FormRequest
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
            'handphone' => 'required|numeric|unique:pengelola_kontaks',
            'email' => 'required|string|max:255|email|unique:pengelola_kontaks',
            'posisi' => 'nullable|string|max:255',
            'pengelola_id' => 'required|string|max:255',
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
            'pengelola_id' => 'pengelola',
        ];
    }
}
