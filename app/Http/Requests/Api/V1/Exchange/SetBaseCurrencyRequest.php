<?php

namespace App\Http\Requests\Api\V1\Exchange;

use App\Http\Api\AbstractApiRequest;
use Illuminate\Validation\Rule;

class SetBaseCurrencyRequest extends AbstractApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'base_currency' => ['required', Rule::in(config('app.fixer_currency'))],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
