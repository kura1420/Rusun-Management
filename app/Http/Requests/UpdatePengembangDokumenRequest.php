<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengembangDokumenRequest extends FormRequest
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
        $id = request()->route('pengembang-dokumen');

        return [
            //
            'nama' => 'required|string|max:255',
            'file' => 'nullable|file|size:5120',
            'keterangan' => 'nullable|string|max:255',
            'pengembang_id' => 'required|string|max:255',
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
