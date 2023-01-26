<?php

namespace App\Http\Requests;

use App\Models\PollingKanidat;
use Illuminate\Foundation\Http\FormRequest;

class StorePollingKanidatRequest extends FormRequest
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
        $rules = PollingKanidat::$rules;

        return $rules;
    }

    public function attributes()
    {
        return PollingKanidat::$ruleMessages;
    }
}
