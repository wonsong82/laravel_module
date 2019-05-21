<?php

namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'code' => 'nullable|unique:companies,code,' . request()->route('company'),
            'status_code' => 'required',
            'name' => 'required',
            'legal_name' => 'nullable',
            'desc' => 'nullable',
            'phone' => 'nullable',
            'fax' => 'nullable',
            'email' => 'nullable|email',
            'website' => 'nullable',
            'currency_code' => 'nullable',
            'note' => 'nullable',
            'locale_id' => 'required',
            'timezone' => 'required',
            'physical_address_attention' => 'nullable',
            'physical_address_line1' => 'nullable',
            'physical_address_line2' => 'nullable',
            'physical_address_line3' => 'nullable',
            'physical_address_line4' => 'nullable',
            'physical_address_city' => 'nullable',
            'physical_address_state' => 'nullable',
            'physical_address_zip' => 'nullable',
            'physical_address_country' => 'nullable',
            'shipping_address_attention' => 'nullable',
            'shipping_address_line1' => 'nullable',
            'shipping_address_line2' => 'nullable',
            'shipping_address_line3' => 'nullable',
            'shipping_address_line4' => 'nullable',
            'shipping_address_city' => 'nullable',
            'shipping_address_state' => 'nullable',
            'shipping_address_zip' => 'nullable',
            'shipping_address_country' => 'nullable',
            'billing_address_attention' => 'nullable',
            'billing_address_line1' => 'nullable',
            'billing_address_line2' => 'nullable',
            'billing_address_line3' => 'nullable',
            'billing_address_line4' => 'nullable',
            'billing_address_city' => 'nullable',
            'billing_address_state' => 'nullable',
            'billing_address_zip' => 'nullable',
            'billing_address_country' => 'nullable',
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
            'code' => __('company::company.field.code'),
            'status_code' => __('company::company.field.status_code'),
            'name' => __('company::company.field.name'),
            'legal_name' => __('company::company.field.legal_name'),
            'desc' => __('company::company.field.desc'),
            'phone' => __('company::company.field.phone'),
            'fax' => __('company::company.field.fax'),
            'email' => __('company::company.field.email'),
            'website' => __('company::company.field.website'),
            'currency_code' => __('company::company.field.currency_code'),
            'note' => __('company::company.field.note'),
            'locale_id' => __('company::company.field.locale'),
            'timezone' => __('company::company.field.timezone'),
            'physical_address_attention' => __('application::address.attention'),
            'physical_address_line1' => __('application::address.line1'),
            'physical_address_line2' => __('application::address.line2'),
            'physical_address_line3' => __('application::address.line3'),
            'physical_address_line4' => __('application::address.line4'),
            'physical_address_city' => __('application::address.city'),
            'physical_address_state' => __('application::address.state'),
            'physical_address_zip' => __('application::address.zip'),
            'physical_address_country' => __('application::address.country'),
            'shipping_address_attention' => __('application::address.attention'),
            'shipping_address_line1' => __('application::address.line1'),
            'shipping_address_line2' => __('application::address.line2'),
            'shipping_address_line3' => __('application::address.line3'),
            'shipping_address_line4' => __('application::address.line4'),
            'shipping_address_city' => __('application::address.city'),
            'shipping_address_state' => __('application::address.state'),
            'shipping_address_zip' => __('application::address.zip'),
            'shipping_address_country' => __('application::address.country'),
            'billing_address_attention' => __('application::address.attention'),
            'billing_address_line1' => __('application::address.line1'),
            'billing_address_line2' => __('application::address.line2'),
            'billing_address_line3' => __('application::address.line3'),
            'billing_address_line4' => __('application::address.line4'),
            'billing_address_city' => __('application::address.city'),
            'billing_address_state' => __('application::address.state'),
            'billing_address_zip' => __('application::address.zip'),
            'billing_address_country' => __('application::address.country'),
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
