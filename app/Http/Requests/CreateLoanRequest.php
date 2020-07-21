<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLoanRequest extends FormRequest
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
        $step = session('create-loan.step');

        if ($this->input('previous')) {
            return [];
        }

        if (!$step || $step == 1) {
            return [
                'bvn' => 'required|min:11',
            ];
        }

        if ($step == 2) {
            return [
                'first_name'    => 'required|min:3',
                'last_name'     => 'required|min:3',
                'middle_name'   => 'nullable|min:3',
                'date_of_birth' => 'required|date',
                'email'         => 'required|email',
                'mobile_number' => 'required|min:11|max:15',
                'address'       => 'required',
            ];
        }

        if ($step == 3) {
            return [
                'nok_first_name'    => 'required',
                'nok_last_name'     => 'required',
                'nok_mobile_number' => 'required|min:11|max:15',
                'nok_relationship'  => 'required',
            ];
        }

        if ($step == 4) {
            return [
                'bank_id'        => 'required',
                'account_number' => 'required|min:10|max:10',
            ];
        }

        if ($step == 5) {
            return [
                'loan_product_id' => 'required',
                'loan_tenure'     => 'required',
                'loan_amount'     => 'required',
                'loan_purpose'    => 'required',
            ];
        }

        return [
            'undertaking' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nok_first_name.required'    => 'The next of kin first name is required.',
            'nok_last_name.required'     => 'The next of kin last name is required.',
            'nok_mobile_number.required' => 'The next of kin mobile number is required.',
            'nok_mobile_number.min'      => 'The next of kin mobile number must be at least 11 characters.',
            'nok_mobile_number.max'      => 'The next of kin mobile number must be at most 15 characters.',
            'nok_relationship.required'  => 'The next of kin relationship is required.',
        ];
    }
}
