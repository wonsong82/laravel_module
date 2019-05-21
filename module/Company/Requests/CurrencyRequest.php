<?php

namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
            'code' => 'required|unique_multiple:currencies,code&company_id,' . request()->route('currency'),
            'status_code' => 'required',
            'name' => 'required',
            'symbol' => 'required',
            'decimal_count' => 'nullable|integer',
            'decimal_separator' => 'nullable|max:1',
            'thousand_separator' => 'nullable|max:1'
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
            'code' => __('company::currency.field.code'),
            'status_code' => __('company::currency.field.status_code'),
            'name' => __('company::currency.field.name'),
            'symbol' => __('company::currency.field.symbol'),
            'decimal_count' => __('company::currency.field.decimal_count'),
            'decimal_separator' => __('company::currency.field.decimal_separator'),
            'thousand_separator' => __('company::currency.field.thousand_separator'),
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

        ];
    }
}
