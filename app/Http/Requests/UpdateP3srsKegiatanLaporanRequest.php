<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateP3srsKegiatanLaporanRequest extends FormRequest
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
            'judul' => 'required|string|max:255',
            'penjelasan' => 'required|string',
            'dokumentasis' => 'nullable|array|max:5',
            'dokumentasis.*' => 'nullable|size:15000|mimes:jpeg,bmp,png,gif,svg,pdf'
        ];
    }
}
