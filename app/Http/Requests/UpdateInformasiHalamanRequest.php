<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInformasiHalamanRequest extends FormRequest
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
        $halamanNama = request('halaman_nama');

        return [
            //
            'halaman_nama' => 'required|string',
            'halaman_aksi' => [
                'required',
                'string',
                function ($attr, $value, $fail) use ($halamanNama, $id) {
                    $check = \App\Models\InformasiHalaman::where([
                        ['halaman_nama', $halamanNama],
                        ['halaman_aksi', $value],
                    ])->first();

                    if ($check) {
                        if ($id != $check->id) {
                            $fail("Data {$attr} sudah tersedia");
                        }
                    }
                }
            ],
            'judul' => 'required|string|max:255',
            'penjelasan' => 'required|string',
            'file' => 'nullable|file',
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
            'halaman_nama' => 'halaman',
            'halaman_aksi' => 'aksi',
        ];
    }
}
