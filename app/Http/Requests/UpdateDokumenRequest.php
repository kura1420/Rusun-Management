<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDokumenRequest extends FormRequest
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
            'singkatan' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'kepada' => 'required|string',
        ];
    }
}
