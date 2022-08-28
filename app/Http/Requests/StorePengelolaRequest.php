<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengelolaRequest extends FormRequest
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
            'nama' => 'required|string|max:255|unique:pengelolas',
            'alamat' => 'required|string',
            'telp' => 'nullable|numeric',
            'email' => 'nullable|string|max:255|email|unique:pengelolas',
            'website' => 'nullable|string|max:255|url',
            'keterangan' => 'nullable|string|max:255',
            'sebagai' => 'required|string|max:255',
        ];
    }
}
