<?php

namespace App\Http\Requests;

use App\Models\ProgramDokumen;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramDokumenRequest extends FormRequest
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
        $rules = ProgramDokumen::$rules;

        return $rules;
    }

    public function attributes()
    {
        return ProgramDokumen::$ruleMessages;
    }
}
