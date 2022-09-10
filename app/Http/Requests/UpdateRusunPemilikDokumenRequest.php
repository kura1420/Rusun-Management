<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRusunPemilikDokumenRequest extends FormRequest
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
            'file' => 'nullable|mimes:pdf|max:150000',
            'keterangan' => 'nullable|string|max:255',
            'dokumen_id' => 'required|string',
            'rusun_unit_detail_id' => 'required|string',
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
            'dokumen_id' => 'dokumen',
            'rusun_unit_detail_id' => 'tower & unit',
        ];
    }
}
