<?php

namespace App\Http\Requests;

use App\Models\ProgramKanidatDokumen;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgramKanidatDokumenRequest extends FormRequest
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
        $rules = ProgramKanidatDokumen::$rules;

        $rules['file'] = $rules['file'] . '|required';

        return $rules;
    }

    public function attributes()
    {
        return ProgramKanidatDokumen::$ruleMessages;
    }
}
