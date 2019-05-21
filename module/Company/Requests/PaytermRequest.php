<?php

namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaytermRequest extends FormRequest
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
            'company_id' => 'required',
            'status_code' => 'required',
            'code' => 'required|unique_multiple:payterms,code&company_id,' . request()->route('payterm'),
            'name' => 'required|min:2|max:255',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'code' => __('company::payterm.field.code'),
            'status_code' => __('company::payterm.field.status_code'),
            'name' => __('company::payterm.field.name'),
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
