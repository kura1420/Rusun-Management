<?php

namespace App\Http\Requests;

use App\Models\ProgramLaporan;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramLaporanRequest extends FormRequest
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
        $rules = ProgramLaporan::$rules;

        return $rules;
    }

    public function attributes()
    {
        return ProgramLaporan::$ruleMessages;
    }
}
