<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKomplainRequest extends FormRequest
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
            'judul' => 'required|string|max:255',
            'penjelasan' => 'required|string',
            'tingkat' => 'required|numeric',
            'pengelola_id' => 'nullable|string',
            'rusun_id' => 'required|string',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'nullable|mimes:jpeg,bmp,png,gif,svg,pdf|max:15000'
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
            'pengelola_id' => 'pengelola',
            'rusun_id' => 'rusun',
        ];
    }
}
