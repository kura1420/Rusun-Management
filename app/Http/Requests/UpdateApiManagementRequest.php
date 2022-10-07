<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApiManagementRequest extends FormRequest
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
            'table' => 'required|string|max:100',
            'reff_id' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'endpoint' => 'required|string|max:255|url',
        ];
    }

    public function attributes()
    {
        return [
            'reff_id' => 'rusun',
        ];
    }
}
