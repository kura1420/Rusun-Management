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
            'reff_id' => 'required|string|max:100',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',

            'table_rusun_details' => 'nullable|string|max:100',
            'endpoint_rusun_details' => 'nullable|string|url|max:255',
            'keterangan_rusun_details' => 'nullable|string',

            'table_rusun_tarifs' => 'nullable|string|max:100',
            'endpoint_rusun_tarifs' => 'nullable|string|url|max:255',
            'keterangan_rusun_tarifs' => 'nullable|string',

            'table_rusun_outstanding_penghunis' => 'nullable|string|max:100',
            'endpoint_rusun_outstanding_penghunis' => 'nullable|string|url|max:255',
            'keterangan_rusun_outstanding_penghunis' => 'nullable|string',

            'table_rusun_pemiliks' => 'nullable|string|max:100',
            'endpoint_rusun_pemiliks' => 'nullable|string|url|max:255',
            'keterangan_rusun_pemiliks' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'reff_id' => 'rusun',
            
            'endpoint_rusun_details' => 'endpoint tower',
            'keterangan_rusun_details' => 'keterangan tower',

            'endpoint_rusun_tarifs' => 'endpoint tarif',
            'keterangan_rusun_tarifs' => 'keterangan tarif',

            'endpoint_rusun_outstanding_penghunis' => 'endpoint outstanding penghuni',
            'keterangan_rusun_outstanding_penghunis' => 'keterangan outstanding penghuni',

            'endpoint_rusun_pemiliks' => 'endpoint pemilik & penghuni',
            'keterangan_rusun_pemiliks' => 'keterangan pemilik & penghuni',
        ];
    }
}
