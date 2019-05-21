<?php
namespace Module\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OffdayDayRequest extends FormRequest
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
            'day' => 'required',
            // 'unique' => 'required|unique:table,column,'.$this->get('id'),
            // 'unique_multiple' => 'required|unique_multiple:vendor_categories,code&company_id,'.$this->get('id'),
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
            //
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
