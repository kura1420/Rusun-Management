<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRusunFasilitasRequest extends FormRequest
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
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image',
            'rusun_id' => 'required|string',
            'rusun_detail_id' => 'required|string',
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
            'nama' => 'fasilitas',
            'rusun_id' => 'rusun',
            'rusun_detail_id' => 'tower',
        ];
    }
}
