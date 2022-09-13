<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreP3srsKegiatanKanidatRequest extends FormRequest
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
            'grup_nama' => 'required|string|max:255',
            'wargas' => 'required|string',
            'p3srs_kegiatan_jadwal_id' => 'required|string',
        ];
    }
}
