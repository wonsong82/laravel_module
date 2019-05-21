<?php
namespace Module\Application\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocaleRequest extends FormRequest
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
            'code' => 'required|unique:locales,code,' . request()->route('locale'),
            'country_code' => 'required',
            'language_code' => 'required',
            'country_name' => 'required',
            'language_name' => 'required'
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
            'code' => __('application::locale.field.code'),
            'country_code' => __('application::locale.field.country_code'),
            'language_code' => __('application::locale.field.language_code'),
            'country_name' => __('application::locale.field.country_name'),
            'language_name' => __('application::locale.field.language_name'),
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
