<?php

namespace App\Http\Requests\Api\V1\Exchange;

use App\Http\Api\AbstractApiRequest;
use Illuminate\Validation\Rule;

class CalculateRepaymentRequest extends AbstractApiRequest
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
            "amount" =>  ['required', 'numeric'],
            "tenure" =>  ['required', 'numeric', 'min:1', 'max:12'],
            "repayment_day" => ['required', 'numeric', 'min:1', 'max:28'],
            "interest" =>  ['required', 'numeric',  'min:0', 'max:100']
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
