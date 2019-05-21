<?php

namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UOMRequest extends FormRequest
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
            'code' => 'required|unique_multiple:uoms,code&company_id,' . request()->route('uom'),
            'isc' => 'nullable|unique_multiple:uoms,code&company_id,' . request()->route('uom'),
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
            'code' => __('company::uom.field.code'),
            'isc' => __('company::uom.field.isc'),
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
