<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRusunPemilikDokumenRequest extends FormRequest
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
            'file' => 'required||mimes:pdf|max:15000',
            'keterangan' => 'nullable|string|max:255',
            'dokumen_id' => 'required|string',
            'pemilik_id' => 'required|string',
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
            'pemilik_id' => 'pemilik',
            'rusun_unit_detail_id' => 'tower & unit',
        ];
    }
}
