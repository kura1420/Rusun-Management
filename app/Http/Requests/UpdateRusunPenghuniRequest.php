<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRusunPenghuniRequest extends FormRequest
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
            'email' => 'required|string|max:100|unique:rusun_penghunis,email,' . $id,
            'phone' => 'required|numeric|unique:rusun_penghunis,phone,' . $id,
            'identitas_nomor' => 'required|string|max:255|unique:rusun_penghunis,identitas_nomor,' . $id,
            'identitas_tipe' => 'required|string',
            'identitas_file' => 'nullable|image|max:15000',
            'status' => 'required|string',
            'tanggal_masuk' => 'nullable|date',
            'tanggal_keluar' => 'nullable|date',
        ];
    }
}
