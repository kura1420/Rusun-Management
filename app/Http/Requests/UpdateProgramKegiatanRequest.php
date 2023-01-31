<?php

namespace App\Http\Requests;

use App\Models\ProgramKegiatan;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramKegiatanRequest extends FormRequest
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
        $rules = ProgramKegiatan::$rules;

        $tanggalAkhir['tanggal_berakhir'] = $rules['tanggal_berakhir'] . '|after: ' . request('tanggal_mulai');

        return $rules;
    }

    public function attributes()
    {
        return ProgramKegiatan::$ruleMessages;
    }
}
