<?php

namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
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
            'type_code' => 'required',
            'symbol' => 'required|unique_multiple:units,symbol&company_id,' . request()->route('unit'),
            'name' => 'required',
            'plural_name' => 'required',
            'desc' => 'nullable'
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
            'type_code' => __('company::unit.field.type_code'),
            'status_code' => __('company::unit.field.status_code'),
            'symbol' => __('company::unit.field.symbol'),
            'name' => __('company::unit.field.name'),
            'plural_name' => __('unit::uom.field.plural_name'),
            'desc' => __('company::unit.field.desc'),
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
