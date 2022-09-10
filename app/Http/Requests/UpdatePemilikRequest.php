<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePemilikRequest extends FormRequest
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
        $id = request()->segment(2);

        return [
            //
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:100|unique:pemiliks,email,' . $id,
            'phone' => 'required|numeric|unique:pemiliks,phone,' . $id,
            'identitas_nomor' => 'required|string|max:255|unique:pemiliks,identitas_nomor,' . $id,
            'identitas_tipe' => 'required|string',
            'identitas_file' => 'nullable|image|max:15000',
        ];
    }
}
