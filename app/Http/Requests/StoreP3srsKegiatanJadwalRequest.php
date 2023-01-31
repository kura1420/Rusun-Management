<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreP3srsKegiatanJadwalRequest extends FormRequest
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
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'p3srs_kegiatan_id' => 'required|string',
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
            'p3srs_kegiatan_id' => 'kegiatan',
            'rusun_id' => 'rusun',
        ];
    }
}
