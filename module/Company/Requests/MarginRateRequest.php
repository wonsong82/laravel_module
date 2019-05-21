<?php
namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarginRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow if the user is logged in
        // return auth()->check();

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
            'rates' => 'required',
            // 'unique' => 'required|unique:table,column,' . request()->route('margin_rate'),
            // 'unique_multiple' => 'required|unique_multiple:vendor_categories,code&company_id,' . request()->route('margin_rate'),
            // 'name' => 'required|min:2|max:255'
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
            'rates' => __('company::margin_rate.field.rates'),
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
