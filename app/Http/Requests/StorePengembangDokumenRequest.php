<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\File;

class StorePengembangDokumenRequest extends FormRequest
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
            'file' => 'nullable|mimes:pdf|max:15000',
            'keterangan' => 'nullable|string|max:255',
            'dokumen_id' => 'required|string',
            'pengembang_id' => 'required|string',
            'rusun_id' => 'required|string',
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
            'pengembang_id' => 'pengembang',
            'rusun_id' => 'rusun',
        ];
    }
}
