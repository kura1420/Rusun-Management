<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqRequest extends FormRequest
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
            'judul' => 'required|string|max:255|unique:faqs,judul,' . $id,
            'kata_kunci' => 'nullable',
            'penjelasan' => 'required|string',
        ];
    }
}
