<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRusunRequest extends FormRequest
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
            'nama' => 'required|string|max:255|unique:rusuns',
            'alamat' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'total_tower' => 'nullable|numeric',
            'total_unit' => 'nullable|numeric',
            'foto_1' => 'nullable|image',
            'foto_2' => 'nullable|image',
            'foto_3' => 'nullable|image',
            'website' => 'nullable|string|max:100|url|unique:rusuns',
            'facebook' => 'nullable|string|max:100|unique:rusuns',
            'instgram' => 'nullable|string|max:100|unique:rusuns',
            'email' => 'nullable|string|max:100|email|unique:rusuns',
            'telp' => 'nullable|numeric|unique:rusuns',
            'endpoint' => 'nullable|url|string',
            'province_id' => 'required|string',
            'regencie_id' => 'required|string',
            'district_id' => 'required|string',
            'village_id' => 'required|string',
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
            'province_id' => 'provinsi',
            'regencie_id' => 'kota',
            'district_id' => 'kecamatan',
            'village_id' => 'kelurahan',
        ];
    }
}
