<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRusunUnitDetailRequest extends FormRequest
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
            'ukuran' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
            'foto' => 'nullable|image',
            'keterangan' => 'nullable|string',
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
            'rusun_id' => 'rusun',
            'rusun_detail_id' => 'tower',
        ];
    }
}
