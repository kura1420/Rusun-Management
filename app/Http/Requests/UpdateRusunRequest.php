<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRusunRequest extends FormRequest
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
            'nama' => 'required|string|max:255|unique:rusuns,nama,' . $id,
            'alamat' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'total_tower' => 'required|numeric',
            'total_unit' => 'required|numeric',
            'foto_1' => 'nullable|image',
            'foto_2' => 'nullable|image',
            'foto_3' => 'nullable|image',
            'website' => 'nullable|string|max:100|url|unique:rusuns,website,' . $id,
            'facebook' => 'nullable|string|max:100|unique:rusuns,facebook,' . $id,
            'instgram' => 'nullable|string|max:100|unique:rusuns,instgram,' . $id,
            'email' => 'nullable|string|max:100|email|unique:rusuns,email,' . $id,
            'telp' => 'nullable|numeric|unique:rusuns,telp,' . $id,
            'endpoint' => 'nullable|string|url',
            'province_id' => 'required|string',
            'regencie_id' => 'required|string',
            'district_id' => 'required|string',
            'village_id' => 'required|string',

            'endpoint_username' => 'nullable|string|max:255',
            'endpoint_password' => 'nullable|string|max:255',
            'endpoint_tarif' => 'nullable|string|url|max:255',
            'endpoint_outstanding' => 'nullable|string|url|max:255',
            'endpoint_pemilik' => 'nullable|string|url|max:255',
            'endpoint_penghuni' => 'nullable|string|url|max:255',
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
            'village_id' => 'desa',
        ];
    }
}
