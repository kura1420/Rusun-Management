<?php

namespace App\Http\Requests;

use App\Models\ProgramJabatan;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramJabatanRequest extends FormRequest
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
        return ProgramJabatan::$rules;
    }

    public function attributes()
    {
        return ProgramJabatan::$ruleMessages;
    }
}
